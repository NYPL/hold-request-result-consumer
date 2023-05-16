<?php

namespace NYPL\HoldRequestResultConsumer;

use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult;
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
    protected function patchHoldRequestService(HoldRequestResult $holdRequestResult)
    {
        // Updating Hold Request Service
        $holdRequestService = HoldRequestClient::patchHoldRequestById(
            $holdRequestResult->getHoldRequestId(),
            true,
            $holdRequestResult->isSuccess(),
            $holdRequestResult->getError()
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
     * @return null|Patron
     * @throws NonRetryableException
     */
    protected function getPatron(HoldRequest $holdRequest)
    {
        /**
         * @var Patron|null
         */
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
     * @param HoldRequest $holdRequest
     * @param HoldRequestResult $holdRequestResult
     */
    protected function sendEmail(
        Patron $patron,
        // The hold-request record retrieved from the HoldRequestService:
        HoldRequest $holdRequest,
        // The PATCH object containing success: true/false, which this app
        // posted to the HoldRequestService earlier:
        HoldRequestResult $holdRequestResult
    ) {
        $notificationType = null;
        if ($holdRequestResult->isSuccess() === true) {
            if ($holdRequest->getDocDeliveryData() !== null &&
                $holdRequest->getDocDeliveryData()->getEmailAddress() !== null) {
                $notificationType = 'edd-success';
            } else {
                $notificationType = 'hold-success';
            }
        } else {
            $notificationType = 'hold-fail';
        }

        // Call on PatronServies Notification endpoint:
        PatronClient::notifyPatron($patron, $holdRequest->getId(), $notificationType);
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
                APILogger::addInfo(
                  "Handling hold-request {$holdRequest->getId()}, which is "
                  . (!$holdRequest->isProcessed() ? 'not ' : '') . "processed"
                );

                // TODO: Remove this logic when this loop is fixed
                //
                // PB: 2023-05-08: I'm not sure what is meant by "this logic"
                // but the following check, which asserts that this consumer
                // does not re-process something that has already been marked
                // as processed, feels sound enough to me.
                if (!$holdRequest->isProcessed()) {
                    // Patch hold-request with success: true/false
                    $this->patchHoldRequestService($holdRequestResult);

                    $this->skipMissingItem($holdRequestResult);
                    $this->skipMissingPatron($holdRequestResult);

                    $patron = $this->getPatron($holdRequest);

                    if ($holdRequest->getRecordType() === 'i') {
                        $this->sendEmail($patron, $holdRequest, $holdRequestResult);
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
