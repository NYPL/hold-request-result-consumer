<?php

namespace NYPL\HoldRequestResultConsumer\Test\Mocks;

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

        self::$mockListenerData->decodeRawData('HoldRequestResult');
    }

    public static function getListenerData()
    {
        self::setListenerData();
        return self::$mockListenerData;
    }
}
