<?php

namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\HoldRequestResultConsumerListener;
use NYPL\HoldRequestResultConsumer\Test\Mocks\MockConfig;
use NYPL\HoldRequestResultConsumer\Test\Mocks\MockListenerData;
use NYPL\Starter\AvroDeserializer;
use NYPL\Starter\Listener\ListenerData;
use NYPL\Starter\Listener\ListenerEvent\KinesisEvent;
use NYPL\Starter\Listener\ListenerEvents;
use NYPL\Starter\Listener\ListenerEvents\KinesisEvents;
use NYPL\Starter\SchemaClient;
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
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public $fakeListenerData;

    public $fakeKinesisEvent;

    public $fakeKinesisEvents;

    public $fakeHoldRequestResultConsumerListener;

    public function setUp()
    {
        parent::setUp();

        MockConfig::initialize(__DIR__ . '/../');

        $this->fakeListenerData = MockListenerData::getListenerData();

        var_dump($this->fakeListenerData->getData());

        $this->fakeKinesisEvent = new class (MockListenerData::getListenerData()) extends KinesisEvent {
        };

        $this->fakeKinesisEvent->setListenerData($this->fakeListenerData);

        $this->fakeKinesisEvents = new class extends KinesisEvents {
            public function getEvents()
            {
                $this->addEvent(array(), '');
                return $this->events;
            }

            public function translateEvents(array $record)
            {

                $allRecords = json_decode(
                    file_get_contents(__DIR__ . '/../events/kinesis_hold_edd_success.json'),
                    true
                );
                $record = $allRecords['Records'][0];
                print_r($record);
                parent::translateEvents($record);
            }

            public function translateEvent(array $record, $schemaName = '')
            {
                $allRecords = json_decode(
                    file_get_contents(__DIR__ . '/../events/kinesis_hold_edd_success.json'),
                    true
                );
                $record = $allRecords['Records'][0];
                $schemaName = 'HoldRequestResult';
                return parent::translateEvent($record, $schemaName);
            }

            public function addEvent(array $record, $schemaName = '')
            {
                $allRecords = json_decode(
                    file_get_contents(__DIR__ . '/../events/kinesis_hold_edd_success.json'),
                    true
                );
                $record = $allRecords['Records'][0];
                $schemaName = 'HoldRequestResult';
                parent::addEvent($record, $schemaName);
            }
        };

        $this->fakeKinesisEvents->setEventSourceARN(
            "arn:aws:kinesis:us-east-1:946183545209:stream/HoldRequestResult-qa"
        );

        $this->fakeHoldRequestResultConsumerListener = new class extends HoldRequestResultConsumerListener {
            public function __construct()
            {
                parent::__construct();
            }

            public function setListenerEvents(ListenerEvents $listenerEvents)
            {
                parent::setListenerEvents($listenerEvents);
            }
        };

        $this->fakeHoldRequestResultConsumerListener->setListenerEvents($this->fakeKinesisEvents);
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
