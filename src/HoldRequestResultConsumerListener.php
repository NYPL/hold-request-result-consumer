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
     * @return HoldRequest|null
     * @throws APIException
     */
    protected function getHoldRequest($holdRequestResult)
    {
        $holdRequest = HoldRequestClient::getHoldRequestById($holdRequestResult->getHoldRequestId());

        APILogger::addDebug('HoldRequest', (array) $holdRequest);

        if ($holdRequest === null) {
            throw new APIException('Cannot get Hold Request for Request Id ' .
                $holdRequestResult->getHoldRequestId());
        }

        return $holdRequest;
    }

    /**
     * @param HoldRequest $holdRequest
     * @return null|Item
     * @throws APIException
     */
    protected function getItem($holdRequest)
    {
        $item = ItemClient::getItemByIdAndSource(
            $holdRequest->getRecord(),
            $holdRequest->getNyplSource()
        );

        APILogger::addDebug('Item', (array) $item);
        APILogger::addDebug('BibIds', (array) $item->getBibIds());

        if ($item === null) {
            throw new APIException(
                'Hold request record missing Item data for Request Id ' .
                $holdRequestResult->getHoldRequestId()
            );
        }

        return $item;
    }

    /**
     * @param Item $item
     * @param HoldRequestResult $holdRequestResult
     * @return null|Bib
     * @throws APIException
     */
    protected function getBib($item, $holdRequestResult)
    {
        $bib = BibClient::getBibByIdAndSource($item->getBibIds()[0], $item->getNyplSource());
        APILogger::addDebug('Bib', (array) $bib);

        if ($bib === null) {
            throw new APIException(
                'Hold request record missing Bib data for Request Id ' .
                $holdRequestResult->getHoldRequestId()
            );
        }

        return $bib;
    }

    /**
     * @param HoldRequestResult $holdRequestResult
     * @throws APIException
     */
    protected function skipMissingItem(HoldRequestResult $holdRequestResult)
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
    protected function skipMissingPatron(HoldRequestResult $holdRequestResult)
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

                    $patron = PatronClient::getPatronById($holdRequest->getPatron());

                    if ($patron === null) {
                        throw new APIException(
                            'Hold request record missing Patron data for Request Id ' .
                            $holdRequestResult->getHoldRequestId()
                        );
                    }

                    if ($holdRequest->getRecordType() === 'i') {
                        $item = $this->getItem($holdRequest);

                        $bib = $this->getBib($item, $holdRequestResult);

                        $this->sendEmail($patron, $bib, $item, $holdRequest, $holdRequestResult);
                    }
                } else { // $holdRequestResult->isSuccess() === false, error !== null
                    $holdRequest = $this->getHoldRequest($holdRequestResult);

                    // TODO: Remove this logic when this loop is fixed
                    if (!$holdRequest->isProcessed()) {
                        $this->patchHoldRequestService($holdRequestResult);

                        $this->skipMissingItem($holdRequestResult);
                        $this->skipMissingPatron($holdRequestResult);

                        $holdRequest = $this->getHoldRequest($holdRequestResult);

                        $patron = PatronClient::getPatronById($holdRequest->getPatron());

                        if ($patron === null) {
                            throw new APIException(
                                'Hold request record missing Patron data for Request Id ' .
                                $holdRequestResult->getHoldRequestId()
                            );
                        }

                        if ($holdRequest->getRecordType() === 'i') {
                            $item = $this->getItem($holdRequest);

                            $bib = $this->getBib($item, $holdRequestResult);

                            $this->sendEmail($patron, $bib, $item, $holdRequest, $holdRequestResult);
                        }
                    }
                }

            } catch (\Exception $exception) {
                APILogger::addError(
                    'Exception thrown: ' . $exception->getMessage() .
                    ', Error code: ' . $exception->getCode()
                );
                if ($exception->getCode() >= 500 && $exception->getCode() <= 599) {
                    return new ListenerResult(false, 'Retrying process');
                }
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
