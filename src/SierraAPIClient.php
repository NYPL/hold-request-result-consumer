<?php
namespace NYPL\HoldRequestResultConsumer;

use NYPL\Starter\APILogger;
use NYPL\Starter\APIException;
use NYPL\Starter\Model\ModelTrait\SierraTrait\SierraReadTrait;

class SierraAPIClient
{
  use SierraReadTrait;

  const SIERRA_API_HOLDS_FETCH_LIMIT = 25;

  public function __construct () {}

  /**
   *  Get all Sierra holds by patronId
   */
  public function getSierraHoldsByPatron($patronId, $offset = 0, $allSierraHolds = []) {
    $resp = $this->sendRequest("patrons/{$patronId}/holds?offset=$offset&limit=" . self::SIERRA_API_HOLDS_FETCH_LIMIT);
    $data = json_decode($resp);

    if (!$data || !isset($data->total) || !isset($data->entries)) {
      throw new APIException("Received invalid response fetching holds for patron $patronId");
    }
    $sierraHolds = $data->entries;
    $allSierraHolds = array_merge($allSierraHolds, $sierraHolds);
    // Have we consumed them all? (or is the Sierra API serving us fewer than
    // the default limit?) We're done.
    if ($data->total == count($allSierraHolds) || count($sierraHolds) < self::SIERRA_API_HOLDS_FETCH_LIMIT) {
      return $allSierraHolds;
    } else {
      // Fetch next page of holds:
      return $this->getSierraHoldsByPatron($patronId, $offset += self::SIERRA_API_HOLDS_FETCH_LIMIT, $allSierraHolds);
    }
  }

  /**
   * Given a hold-request (i.e. a hold-request created inside the Research
   * Catalog), returns the numeric id of the associated Sierra hold
   */
  public function sierraHoldIdForHoldRequest($holdRequest) {
    $sierraHolds = $this->getSierraHoldsByPatron($holdRequest->getPatron());
    
    // If the RC hold-request was not placed on a NYPL item, the hold will be
    // on a virtual record. We need to retrieve the id and nyplSource for the
    // patron's sierra holds:
    if ($holdRequest->nyplSource != 'sierra-nypl') {
      $sierraHolds = $this->attachOriginalItemIdsToVirtualHolds($sierraHolds);
    }

    // Get the [single] matching Sierra hold:
    $matchingSierraHolds = array_filter($sierraHolds, function($sierraHold) use ($holdRequest) {
      $holdRecordId = SierraAPIClient::idFromUri($sierraHold->record);

      // If RC hold-request was for an NYPL item, just match ids:
      if ($holdRequest->nyplSource == 'sierra-nypl') {
        return $holdRequest->record == $holdRecordId
          && $holdRequest->recordType;
      } else {
        // If RC hold-request was for a partner item, match to extracted
        // original id and nyplSource:
        return isset($sierraHold->originalItemId)
          && $holdRequest->record == $sierraHold->originalItemId
          && $holdRequest->nyplSource == $sierraHold->originalItemNyplSource;
      }
    });

    if (count($matchingSierraHolds) == 1) {
      $matchingSierraHold = array_shift($matchingSierraHolds);
      return SierraAPIClient::idFromUri($matchingSierraHold->id);
    } else {
      APILogger::addInfo("Failed to find Sierra hold for {$holdRequest->record}");
    }
  }

  /**
   * Given an array of Sierra holds, attaches item and original partner item
   * ids to each
   */
  private function attachOriginalItemIdsToVirtualHolds($sierraHolds) {
      $sierraHolds = $this->attachItemsToHolds($sierraHolds);
      $sierraHolds = $this->extractPartnerItemIdFromItems($sierraHolds);
      return $sierraHolds;
  }

  /**
   * Given an array of Sierra holds, attaches the held item to each
   */
  private function attachItemsToHolds($sierraHolds) {
    // Build an array of item ids to fetch:
    $itemIds = array_map(function($hold) {
      return SierraAPIClient::idFromUri($hold->record);
    }, $sierraHolds);

    // Fetch all items in one call:
    $resp = $this->sendRequest('items?fields=varFields&id=' . implode(',', $itemIds));
    $items = json_decode($resp);

    // Loop over Sierra holds and assign matching item to each:
    foreach($sierraHolds as $sierraHold) {
      $itemId = SierraAPIClient::idFromUri($sierraHold->record);
      $matchingItems = array_values(array_filter($items->entries, function ($item) use ($itemId) {
        return $item->id == $itemId;
      }));

      // We should always find a matching item:
      if (count($matchingItems)) {
        $sierraHold->item = $matchingItems[0];
      }
    }

    return $sierraHolds;
  }

  /**
   *  Given an array of Sierra holds that have attached items, inspects the
   *  items' internal notes to determine the original item id and source for
   *  which the item is a virtual record. Ataches 
   *  hold->originalItemNyplSource and hold->originalItemId when found.
   */
  private function extractPartnerItemIdFromItems($sierraHolds) {
    foreach($sierraHolds as $sierraHold) {
      if (!$sierraHold->item) continue;

      // Find item fieldTag x varFields:
      $fieldTagXs = array_values(array_filter($sierraHold->item->varFields, function($varField) {
        return $varField->fieldTag == 'x' &&
          $varField->content;
      }));

      // Look for notes with this pattern of note:
      $originalItemNotePattern = '/^Original item: https:\/\/[a-z-.]+\/api\/v0.1\/items\/([a-z-]+)\/([0-9]+)/';
      foreach($fieldTagXs as $fieldTagX) {
        // When found, extract the nyplSource and itemId from the note and
        // attach to the hold
        if (preg_match($originalItemNotePattern, $fieldTagX->content, $match)) {
          $sierraHold->originalItemNyplSource = $match[1];
          $sierraHold->originalItemId = $match[2];
        }
      }
    }
    return $sierraHolds;
  }

  /**
   * Given a Sierra hold/record URI, pops the id part off the end of the URL
   */
  protected static function idFromUri ($uri) {
      $uriParts = explode('/', $uri);
      return array_pop($uriParts);
  }

  private function getRequestType() {
      return 'GET';
  }

  private function getSierraPath() {}

  private function fetchData() {}
}
