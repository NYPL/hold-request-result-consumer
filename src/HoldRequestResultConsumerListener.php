<?php
namespace NYPL\HoldRequestResultConsumer;

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
                $data = $listenerEvent->getListenerData()->getData();

                APILogger::addDebug('data', $data);

                $holdRequestResult = new HoldRequestResult($data);

                APILogger::addDebug('HoldRequestResult', (array) $holdRequestResult);

                // Updating Hold Request Service
                $holdRequestService = HoldRequestClient::patchHoldRequestById(
                    $holdRequestResult->getHoldRequestId(),
                    true,
                    $holdRequestResult->isSuccess()
                );

                APILogger::addDebug('Hold Request Service Patched', (array) $holdRequestService);

                $holdRequest = HoldRequestClient::getHoldRequestById($holdRequestResult->getHoldRequestId());
                APILogger::addDebug('HoldRequest', (array)$holdRequest);

                $patron = PatronClient::getPatronById($holdRequest->getPatron());

                if ($holdRequest->getRecordType() === 'i') {
                    $item = ItemClient::getItemByIdAndSource(
                        $holdRequest->getRecord(),
                        $holdRequest->getNyplSource()
                    );

                    APILogger::addDebug('Item', (array) $item);
                    APILogger::addDebug('BibIds', $item->getBibIds());

                    $bib = BibClient::getBibByIdAndSource($item->getBibIds()[0], $item->getNyplSource());

                    APILogger::addDebug('Bib', (array) $bib);

                    $holdEmailData = new HoldEmailData();
                    $holdEmailData->assembleData($patron, $bib, $item, $holdRequest, $holdRequestResult);

                    if ($holdEmailData->getPatronEmail() !== '') {
                        $mailClient = new MailClient($this->getSchemaName(), $holdEmailData);
                        $mailClient->sendEmail();
                    } else {
                        throw new APIException(
                            'No-email',
                            'Patron did not provide an e-mail address.',
                            0,
                            null,
                            500,
                            null
                        );
                    }
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
