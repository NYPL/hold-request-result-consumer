<?php

namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\HoldRequestResultConsumerListener;
use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult;
use NYPL\HoldRequestResultConsumer\Test\Mocks\Clients\MockBibClient;
use NYPL\HoldRequestResultConsumer\Test\Mocks\Clients\MockHoldRequestClient;
use NYPL\HoldRequestResultConsumer\Test\Mocks\Clients\MockItemClient;
use NYPL\HoldRequestResultConsumer\Test\Mocks\Clients\MockPatronClient;
use NYPL\HoldRequestResultConsumer\Test\Mocks\MockConfig;
use NYPL\HoldRequestResultConsumer\Test\Mocks\MockListenerData;
use NYPL\Starter\APILogger;
use NYPL\Starter\Listener\ListenerEvent\KinesisEvent;
use NYPL\Starter\Listener\ListenerEvents\KinesisEvents;
use PHPUnit\Framework\TestCase;

class HoldRequestResultConsumerListenerTest extends TestCase
{
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        APILogger::addDebug(__CLASS__ . '::' . __FUNCTION__);
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public $fakeListenerData;

    public $fakeKinesisEvent;

    public $fakeKinesisEvents;

    public $fakeHoldRequestResultConsumerListener;

    public $fakeClientHelper;

    public function setUp()
    {
        parent::setUp();

        MockConfig::initialize(__DIR__ . '/../');

        $this->fakeListenerData = MockListenerData::getListenerData();

        $this->fakeKinesisEvent = new class (MockListenerData::getListenerData()) extends KinesisEvent {
        };

        $this->fakeKinesisEvent->setListenerData($this->fakeListenerData);

        $this->fakeKinesisEvents = new class extends KinesisEvents {
            public function getEvents()
            {
                APILogger::addDebug(__CLASS__ . '::' . __FUNCTION__);
                $this->addEvent(array(), '');
                return $this->events;
            }

            public function addEvent(array $record, $schemaName = '')
            {
                APILogger::addDebug(__CLASS__ . '::' . __FUNCTION__);
                $allRecords = json_decode(
                    file_get_contents(__DIR__ . '/../events/kinesis_hold_edd_success.json'),
                    true
                );
                $record = $allRecords['Records'][0];
                $schemaName = 'HoldRequestResult';

            }
        };

        $this->fakeKinesisEvents->setEventSourceARN(
            "arn:aws:kinesis:us-east-1:946183545209:stream/HoldRequestResult"
        );

        $this->fakeHoldRequestResultConsumerListener = new class extends HoldRequestResultConsumerListener
        {
            public function __construct()
            {
                parent::__construct();
            }

            /**
             * @param HoldRequestResult $holdRequestResult
             * @return null|HoldRequest
             */
            protected function getHoldRequest(HoldRequestResult $holdRequestResult)
            {
//                APILogger::addDebug(
//                    'Retrieved Hold Request By Id ',
//                    MockHoldRequestClient::getHoldRequestById($holdRequestResult->getHoldRequestId())
//                );
                return MockHoldRequestClient::getHoldRequestById($holdRequestResult->getHoldRequestId());
            }

            /**
             * @param HoldRequestResult $holdRequestResult
             * @return null|HoldRequest
             */
            protected function patchHoldRequestService($holdRequestResult)
            {
//                APILogger::addDebug(
//                    'Patched Hold Request Service',
//                    MockHoldRequestClient::patchHoldRequestById(
//                        $holdRequestResult->getHoldRequestId(),
//                        true,
//                        $holdRequestResult->isSuccess()
//                    )
//                );

                return MockHoldRequestClient::patchHoldRequestById(
                    $holdRequestResult->getHoldRequestId(),
                    true,
                    $holdRequestResult->isSuccess()
                );
            }

            protected function getPatron($holdRequest)
            {
//                APILogger::addDebug(
//                    'Retrieved Patron Info',
//                    MockPatronClient::getPatronById($holdRequest->getPatron())
//                );
                return MockPatronClient::getPatronById($holdRequest->getPatron());
            }

            protected function getItem($holdRequest)
            {
//                APILogger::addDebug(
//                    'Retrieved Item',
//                    MockItemClient::getItemByIdAndSource(
//                        $holdRequest->getRecord(),
//                        $holdRequest->getNyplSource()
//                    )
//                );

                return MockItemClient::getItemByIdAndSource(
                    $holdRequest->getRecord(),
                    $holdRequest->getNyplSource()
                );
            }

            protected function getBib($item, $holdRequestResult)
            {
//                APILogger::addDebug(
//                    'Retrieved Bib',
//                    MockBibClient::getBibByIdAndSource(
//                        $item->getBibIds()[0],
//                        $item->getNyplSource()
//                    )
//                );

                return MockBibClient::getBibByIdAndSource(
                    $item->getBibIds()[0],
                    $item->getNyplSource()
                );
            }

            protected function sendEmail($patron, $bib, $item, $holdRequest, $holdRequestResult)
            {
//                APILogger::addDebug('E-mail Sent Successfully.');
            }
        };

        $this->invokeMethod(
            $this->fakeHoldRequestResultConsumerListener,
            'setListenerEvents',
            array($this->fakeKinesisEvents)
        );
    }

    public function tearDown()
    {
        unset($this->fakeKinesisEvent);
        unset($this->fakeKinesisEvents);
        unset($this->fakeListenerData);
        unset($this->fakeClientHelper);
        unset($this->fakeHoldRequestResultConsumerListener);
        parent::tearDown();
    }

    public function testProcessListenerEvents()
    {
        $this->assertInstanceOf(
            'NYPL\Starter\Listener\ListenerResult',
            $this->invokeMethod(
                $this->fakeHoldRequestResultConsumerListener,
                'processListenerEvents'
            )
        );
    }
}
