<?php

namespace NYPL\HoldRequestResultConsumer\Test\Mocks\Clients;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;

class MockBibClient
{
    public static function getBibByIdAndSource($bibId = '', $nyplSource)
    {
        $response = json_decode(file_get_contents(__DIR__ . '/../../data/test_bib.json'), true);

        if ($response['statusCode'] === 200) {
            return new Bib($response['data']);
        } else {
            return null;
        }
    }
}
