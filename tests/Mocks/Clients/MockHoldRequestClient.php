<?php
namespace NYPL\HoldRequestResultConsumer\Test\Mocks\Clients;

use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;

/**
 * Class MockHoldRequestClient
 * @package NYPL\HoldRequestResultConsumer\Mocks\Clients
 */
class MockHoldRequestClient
{
    /**
     * @param int $holdRequestId
     * @return HoldRequest|null
     */
    public static function getHoldRequestById(int $holdRequestId)
    {
        $response = json_decode(file_get_contents(__DIR__ . '/../../data/test_hold_request.json'), true);

        if ($response['statusCode'] === 200) {
            return new HoldRequest($response['data']);
        } else {
            return null;
        }
    }

    /**
     * @param int $holdRequestId
     * @param bool $processed
     * @param bool $success
     * @return null|HoldRequest
     */
    public static function patchHoldRequestById(int $holdRequestId, bool $processed, bool $success)
    {
        $response = json_decode(file_get_contents(__DIR__ . '/../../data/test_hold_request.json'), true);
        $response['data']['success'] = $success;
        $response['data']['processed'] = $processed;

        if ($response['statusCode'] === 200) {
            return new HoldRequest($response['data']);
        } else {
            return null;
        }
    }
}
