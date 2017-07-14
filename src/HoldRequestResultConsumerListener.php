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
     * @throws APIException
     */
    protected function getHoldRequestResult(ListenerEvent $listenerEvent)
    {
        $listenerData = $listenerEvent->getListenerData();

        if ($listenerData === null) {
            throw new APIException('No listener data');
        }

        $data = $listenerData->getData();

        APILogger::addDebug('data', $data);

        $holdRequestResult = new HoldRequestResult($data);

        APILogger::addDebug('HoldRequestResult', (array) $holdRequestResult);

        return $holdRequestResult;
    }

    /**
     * @param HoldRequestResult $holdRequestResult
     */
    protected function patchHoldRequestService($holdRequestResult)
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
    protected function getHoldRequest($holdRequestResult)
    {
        $holdRequest = HoldRequestClient::getHoldRequestById($holdRequestResult->getHoldRequestId());
        APILogger::addDebug('HoldRequest', (array) $holdRequest);

        return $holdRequest;
    }

    /**
     * @param $holdRequest
     * @return Item
     */
    protected function getItem($holdRequest)
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
    protected function getBib($item)
    {
        $bib = BibClient::getBibByIdAndSource($item->getBibIds()[0], $item->getNyplSource());
        APILogger::addDebug('Bib', (array) $bib);

        return $bib;
    }

    /**
     * @param HoldRequestResult $holdRequestResult
     * @throws APIException
     */
    protected function handleMissingItem(HoldRequestResult $holdRequestResult)
    {
        if ($holdRequestResult->getError() !== null &&
            $holdRequestResult->getError()->getType() == 'hold-request-record-missing-item-data'
        ) {
            throw new APIException(
                'Hold request record missing Item data for Request Id ' .
                $holdRequestResult->getHoldRequestId()
            );
        }
    }

    /**
     * @param HoldRequestResult $holdRequestResult
     * @throws APIException
     */
    protected function handleMissingPatron(HoldRequestResult $holdRequestResult)
    {
        if ($holdRequestResult->getError() !== null &&
            $holdRequestResult->getError()->getType() == 'hold-request-record-missing-patron-data'
        ) {
            throw new APIException(
                'Hold request record missing Patron data for Request Id ' .
                $holdRequestResult->getHoldRequestId()
            );
        }
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
                $holdRequestResult = $this->getHoldRequestResult($listenerEvent);

                if ($holdRequestResult->isSuccess() === true) {
                    // Assumes error === null

                    $this->patchHoldRequestService($holdRequestResult);

                    $holdRequest = $this->getHoldRequest($holdRequestResult);

                    if ($holdRequest === null) {
                        throw new APIException('Cannot get Hold Request for Request Id ' .
                            $holdRequestResult->getHoldRequestId());
                    }

                    $patron = PatronClient::getPatronById($holdRequest->getPatron());

                    if ($patron === null) {
                        throw new APIException(
                            'Hold request record missing Patron data for Request Id ' .
                            $holdRequestResult->getHoldRequestId()
                        );
                    }

                    if ($holdRequest->getRecordType() === 'i') {
                        $item = $this->getItem($holdRequest);

                        if ($item === null) {
                            throw new APIException(
                                'Hold request record missing Item data for Request Id ' .
                                $holdRequestResult->getHoldRequestId()
                            );
                        }

                        $bib = $this->getBib($item);

                        if ($bib === null) {
                            throw new APIException(
                                'Hold request record missing Bib data for Request Id ' .
                                $holdRequestResult->getHoldRequestId()
                            );
                        }

                        $this->sendEmail($patron, $bib, $item, $holdRequest, $holdRequestResult);
                    }
                } else { // $holdRequestResult->isSuccess() === false, error !== null
                    $this->patchHoldRequestService($holdRequestResult);

                    $this->handleMissingItem($holdRequestResult);
                    $this->handleMissingPatron($holdRequestResult);
                }
            } catch (\Exception $exception) {
                APILogger::addError(
                    'Exception thrown: ' . $exception->getMessage()
                );
            } catch (\Throwable $exception) {
                APILogger::addError(
                    'Throwable thrown: ' . $exception->getMessage()
                );
            }
        }

        return new ListenerResult(
            true,
            'Successfully processed event'
        );
    }
}
