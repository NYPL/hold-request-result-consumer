<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;


use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class ItemClient extends APIClient
{
    public static function getItemByIdAndSource($itemId = '', $nyplSource)
    {
        $url = Config::get('API_BASE_URL') . '/items/' . $nyplSource . '/' . $itemId;

        APILogger::addInfo('Retrieving item by Id and Source', $url);

        $response = self::get($url);

        $response = json_decode((string) $response->getBody(), true);

        APILogger::addInfo(
          'Retrieve item by id and source',
          $response['data']
        );

        if ($response['statusCode'] !== 200) {
            APILogger::addError('Failed', array('Failed to retrieve item ', $itemId));
        }

        return new Item($response['data']);
    }
}