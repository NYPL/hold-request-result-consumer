<?php

namespace NYPL\HoldRequestResultConsumer\Test\Mocks;

use NYPL\HoldRequestResultConsumer\Test\Mocks\Clients\MockSchemaClient;
use NYPL\Starter\APILogger;
use NYPL\Starter\AvroDeserializer;
use NYPL\Starter\Listener\ListenerData;

class MockListenerData
{
    public static $mockListenerData;

    public static function setListenerData()
    {
        self::$mockListenerData = new ListenerData(
            base64_decode("Akg5MDFiZGQxZC1iZDhmLTQzMTAtYmEzMS03ZjEzYTU1ODc3ZmQBAIIB"),
            "HoldRequestResult"
        );

        APILogger::addDebug('Decoding Avro data using ' . self::$mockListenerData->getSchemaName() . ' schema');

        self::$mockListenerData->setData(
            AvroDeserializer::deserializeWithSchema(
                MockSchemaClient::getSchema(self::$mockListenerData->getSchemaName()),
                self::$mockListenerData->getRawAvroData()
            )
        );
    }

    public static function getListenerData()
    {
        self::setListenerData();
        return self::$mockListenerData;
    }
}
