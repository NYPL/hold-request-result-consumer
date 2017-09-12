<?php

namespace NYPL\HoldRequestResultConsumer;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult;
use NYPL\HoldRequestResultConsumer\Model\Exception\ClientTimeoutException;
use NYPL\HoldRequestResultConsumer\Model\Exception\NonRetryableException;
use NYPL\HoldRequestResultConsumer\Model\Exception\RetryableException;
use NYPL\HoldRequestResultConsumer\OAuthClient\BibClient;
use NYPL\HoldRequestResultConsumer\OAuthClient\HoldRequestClient;
use NYPL\HoldRequestResultConsumer\OAuthClient\ItemClient;
use NYPL\HoldRequestResultConsumer\OAuthClient\PatronClient;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Listener\Listener;
use NYPL\Starter\Listener\ListenerEvent;
use NYPL\Starter\Listener\ListenerResult;
use NYPL\Starter\Model\Response\ErrorResponse;

class HoldRequestResultConsumerListener extends Listener
{
    /**
     * @param ListenerEvent $listenerEvent
     * @return HoldRequestResult
     * @throws NonRetryableException
     */
    protected function getHoldRequestResult(ListenerEvent $listenerEvent)
    {
        $listenerData = $listenerEvent->getListenerData();

        if ($listenerData === null) {
            throw new NonRetryableException(
                'Not Acceptable: No listener data',
                array($listenerEvent),
                406,
                null,
                406,
                new ErrorResponse(406, 'no-listener-data', 'Not Acceptable: No listener data')
            );
        }

        $data = $listenerData->getData();

        APILogger::addDebug('data', $data);

        if ($data === null) {
            throw new NonRetryableException(
                'Not Acceptable: No data from listener data',
                array($listenerData),
                406,
                null,
                406,
                new ErrorResponse(406, 'no-data-from-listener-data', 'Not Acceptable: No data from listener data')
            );
        }

        $holdRequestResult = new HoldRequestResult($data);

        APILogger::addDebug('HoldRequestResult', (array)$holdRequestResult);

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

        APILogger::addDebug('Hold Request Service Patched', (array)$holdRequestService);
    }

    /**
     * @param HoldRequestResult $holdRequestResult
     * @return HoldRequest|null
     * @throws APIException
     */
    protected function getHoldRequest(HoldRequestResult $holdRequestResult)
    {
        $holdRequest = HoldRequestClient::getHoldRequestById($holdRequestResult->getHoldRequestId());

        APILogger::addDebug('HoldRequest', (array)$holdRequest);

        if ($holdRequest === null) {
            throw new NonRetryableException(
                'Cannot get Hold Request for Request Id ' . $holdRequestResult->getHoldRequestId(),
                array($holdRequestResult, $holdRequest),
                406,
                null,
                406,
                new ErrorResponse(
                    406,
                    'no-hold-request',
                    'Not Acceptable: Cannot get Hold Request for Request Id ' . $holdRequestResult->getHoldRequestId()
                )
            );
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

        APILogger::addDebug('Item', (array)$item);
        APILogger::addDebug('BibIds', (array)$item->getBibIds());

        if ($item === null) {
            throw new NonRetryableException(
                'Hold request record missing Item data for Request Id ' . $holdRequest->getId(),
                array($holdRequest, $item),
                406,
                null,
                406,
                new ErrorResponse(
                    406,
                    'missing-item-data',
                    'Not Acceptable:Hold request record missing Item data for Request Id ' . $holdRequest->getId()
                )
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
        APILogger::addDebug('Bib', (array)$bib);

        if ($bib === null) {
            throw new NonRetryableException(
                'Hold request record missing Bib data for Request Id ' . $holdRequestResult->getHoldRequestId(),
                array($item, $holdRequestResult, $bib),
                406,
                null,
                406,
                new ErrorResponse(
                    406,
                    'missing-bib-data',
                    'Not Acceptable: Hold request record missing Bib data for Request Id '
                    . $holdRequestResult->getHoldRequestId()
                )
            );
        }

        return $bib;
    }

    /**
     * @param $holdRequest
     * @return null|Patron
     * @throws NonRetryableException
     */
    protected function getPatron($holdRequest)
    {
        $patron = PatronClient::getPatronById($holdRequest->getPatron());

        if ($patron === null) {
            throw new NonRetryableException(
                'Hold request record missing Patron data for Patron Id '
                . $holdRequest->getPatron(),
                array($holdRequest),
                406,
                null,
                406,
                new ErrorResponse(
                    406,
                    'missing-patron-data',
                    'Not Acceptable: Hold request record missing Patron data for Patron Id '
                    . $holdRequest->getPatron()
                )
            );
        }

        return $patron;
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
            throw new NonRetryableException(
                'Hold request record missing Item data for Request Id ' . $holdRequestResult->getHoldRequestId(),
                array($holdRequestResult),
                406,
                null,
                406,
                new ErrorResponse(
                    406,
                    'missing-item-data',
                    'Not Acceptable:Hold request record missing Item data for Request Id '
                    . $holdRequestResult->getHoldRequestId()
                )
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
            throw new NonRetryableException(
                'Hold request record missing Patron data for Request Id ' . $holdRequestResult->getHoldRequestId(),
                array($holdRequestResult),
                406,
                null,
                406,
                new ErrorResponse(
                    406,
                    'missing-patron-data',
                    'Not Acceptable: Hold request record missing Patron data for Request Id '
                    . $holdRequestResult->getHoldRequestId()
                )
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

                $holdRequest = $this->getHoldRequest($holdRequestResult);

                // TODO: Remove this logic when this loop is fixed
                if (!$holdRequest->isProcessed()) {
                    $this->patchHoldRequestService($holdRequestResult);

                    $this->skipMissingItem($holdRequestResult);
                    $this->skipMissingPatron($holdRequestResult);

                    $patron = $this->getPatron($holdRequest);

                    if ($holdRequest->getRecordType() === 'i') {
                        $item = $this->getItem($holdRequest);

                        $bib = $this->getBib($item, $holdRequestResult);

                        $this->sendEmail($patron, $bib, $item, $holdRequest, $holdRequestResult);
                    }
                } else {
                    APILogger::addDebug('Hold Request Id ' .
                        $holdRequestResult->getHoldRequestId() . ' is already processed.');
                }
            } catch (RetryableException $exception) {
                APILogger::addError(
                    'RetryableException thrown: ' . $exception->getMessage() .
                    ', Error code: ' . $exception->getCode()
                );
                return new ListenerResult(
                    false,
                    'Retrying process'
                );
            } catch (NonRetryableException $exception) {
                APILogger::addError(
                    'NonRetryableException thrown: ' . $exception->getMessage() .
                    ', Error code: ' . $exception->getCode()
                );
            } catch (\Exception $exception) {
                APILogger::addError(
                    'Exception thrown: ' . $exception->getMessage() .
                    ', Error code: ' . $exception->getCode()
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
