<?php
namespace NYPL\HoldRequestResultConsumer;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult;
use NYPL\HoldRequestResultConsumer\OAuthClient\BibClient;
use NYPL\HoldRequestResultConsumer\OAuthClient\HoldRequestClient;
use NYPL\HoldRequestResultConsumer\OAuthClient\ItemClient;
use NYPL\HoldRequestResultConsumer\OAuthClient\PatronClient;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Listener\Listener;
use NYPL\Starter\Listener\ListenerEvent;
use NYPL\Starter\Listener\ListenerResult;

class HoldRequestResultConsumerListener extends Listener
{
    /**
     * @param $listenerEvent
     * @return HoldRequestResult
     */
    protected function processHoldRequestResult($listenerEvent)
    {
        $data = $listenerEvent->getListenerData()->getData();

        APILogger::addDebug('data', $data);

        $holdRequestResult = new HoldRequestResult($data);

        APILogger::addDebug('HoldRequestResult', (array) $holdRequestResult);

        return $holdRequestResult;
    }

    /**
     * @param HoldRequestResult $holdRequestResult
     */
    protected function processHoldRequestService($holdRequestResult)
    {
        // Updating Hold Request Service
        $holdRequestService = HoldRequestClient::patchHoldRequestById(
            $holdRequestResult->getHoldRequestId(),
            true,
            $holdRequestResult->isSuccess()
        );

        APILogger::addDebug('Hold Request Service Patched', (array) $holdRequestService);
    }

    /**
     * @param HoldRequest $holdRequest
     * @return Item
     */
    protected function processItem($holdRequest)
    {
        $item = ItemClient::getItemByIdAndSource(
            $holdRequest->getRecord(),
            $holdRequest->getNyplSource()
        );

        APILogger::addDebug('Item', (array) $item);
        APILogger::addDebug('BibIds', $item->getBibIds());

        return $item;
    }

    /**
     * @param Patron $patron
     * @param Bib $bib
     * @param Item $item
     * @param HoldRequest $holdRequest
     * @param HoldRequestResult $holdRequestResult
     */
    protected function sendEmail($patron, $bib, $item, $holdRequest, $holdRequestResult)
    {
        $holdEmailData = new HoldEmailData();
        $holdEmailData->assembleData($patron, $bib, $item, $holdRequest, $holdRequestResult);

        if ($holdEmailData->getPatronEmail() !== '') {
            $mailClient = new MailClient($this->getSchemaName(), $holdEmailData);
            $mailClient->sendEmail();
        } else {
            APILogger::addInfo('E-mail not sent', [
                'HoldRequestId' => $holdRequestResult->getHoldRequestId(),
                'message' => 'E-mail was not sent because patron did not provide e-mail address.'
            ]);
        }
    }

    /**
     * @return ListenerResult
     * @throws APIException
     */
    protected function processListenerEvents()
    {
        /**
         * @var ListenerEvent $listenerEvent
         */
        foreach ($this->getListenerEvents()->getEvents() as $listenerEvent) {
            try {
                $holdRequestResult = $this->processHoldRequestResult($listenerEvent);

                $this->processHoldRequestService($holdRequestResult);

                $holdRequest = HoldRequestClient::getHoldRequestById($holdRequestResult->getHoldRequestId());
                APILogger::addDebug('HoldRequest', (array) $holdRequest);

                $patron = PatronClient::getPatronById($holdRequest->getPatron());

                if ($holdRequest->getRecordType() === 'i') {
                    $item = $this->processItem($holdRequest);

                    $bib = BibClient::getBibByIdAndSource($item->getBibIds()[0], $item->getNyplSource());
                    APILogger::addDebug('Bib', (array) $bib);

                    $this->sendEmail($patron, $bib, $item, $holdRequest, $holdRequestResult);
                }
            } catch (\Exception $exception) {
                APILogger::addError([
                    'HoldRequestId' => $holdRequestResult->getHoldRequestId(),
                    'message' => $exception->getMessage()
                ]);
            }
        }

        return new ListenerResult(
            true,
            'Successfully processed event'
        );
    }
}
