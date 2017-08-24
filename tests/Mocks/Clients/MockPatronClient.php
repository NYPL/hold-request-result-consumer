<?php

namespace NYPL\HoldRequestResultConsumer\Test\Mocks\Clients;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;

class MockPatronClient
{
    public static function getPatronById($patronId = '')
    {
        $response = json_decode(file_get_contents(__DIR__ . '/../../data/test_patron.json'), true);

        if ($response['statusCode'] === 200) {
            return new Patron($response['data']);
        } else {
            return null;
        }
    }
}
