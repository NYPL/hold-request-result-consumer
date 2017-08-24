<?php

namespace NYPL\HoldRequestResultConsumer\Test\Mocks\Clients;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;

class MockItemClient
{
    public static function getItemByIdAndSource($itemId = '', $nyplSource)
    {
        $response = json_decode(file_get_contents(__DIR__ . '/../../data/test_item.json'), true);

        if ($response['statusCode'] === 200) {
            return new Item($response['data']);
        } else {
            return null;
        }
    }
}
