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
     * @param ListenerEvent $listenerEvent
     * @return HoldRequestResult
     */
    protected function processHoldRequestResult(ListenerEvent $listenerEvent)
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
     * @param HoldRequestResult $holdRequestResult
     * @return HoldRequest
     */
    protected function processHoldRequest($holdRequestResult)
    {
        $holdRequest = HoldRequestClient::getHoldRequestById($holdRequestResult->getHoldRequestId());
        APILogger::addDebug('HoldRequest', (array) $holdRequest);

        return $holdRequest;
    }

    /**
     * @param $holdRequest
     * @return Item
     */
    protected function processItem($holdRequest)
    {
        $item = ItemClient::getItemByIdAndSource(
            $holdRequest->getRecord(),
            $holdRequest->getNyplSource()
        );

        APILogger::addDebug('Item', (array) $item);
        APILogger::addDebug('BibIds', (array) $item->getBibIds());

        return $item;
    }

    /**
     * @param Item $item
     * @return Bib
     */
    protected function processBib($item)
    {
        $bib = BibClient::getBibByIdAndSource($item->getBibIds()[0], $item->getNyplSource());
        APILogger::addDebug('Bib', (array) $bib);

        return $bib;
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

                if ($holdRequestResult->getError()->getType() == 'hold-request-record-missing-item-data') {
                    throw new APIException(
                        'Hold request record missing item data for Request Id ' .
                        $holdRequestResult->getHoldRequestId()
                    );
                }

                $this->processHoldRequestService($holdRequestResult);

                $holdRequest = $this->processHoldRequest($holdRequestResult);

                $patron = PatronClient::getPatronById($holdRequest->getPatron());



                if ($holdRequest->getRecordType() === 'i') {
                    $item = $this->processItem($holdRequest);

                    $bib = $this->processBib($item);

                    $this->sendEmail($patron, $bib, $item, $holdRequest, $holdRequestResult);
                }
            } catch (\Exception $exception) {
                APILogger::addError(
                    'Exception thrown: ' . $exception->getMessage()
                );
            } catch (\Throwable $exception) {
                APILogger::addError(
                    'Exception thrown: ' . $exception->getMessage()
                );
            }
        }

        return new ListenerResult(
            true,
            'Successfully processed event'
        );
    }
}
