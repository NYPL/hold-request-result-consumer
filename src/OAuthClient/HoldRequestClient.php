<?php

namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\Starter\Config;
use NYPL\Starter\APILogger;

class HoldRequestClient extends APIClient
{
    public static function getHoldRequestById($holdRequestId = '')
    {
        $url = Config::get('API_BASE_URL') . '/hold-requests/' . $holdRequestId;

        APILogger::addInfo('Retrieving hold request by id', $url);

        $response = self::get($url);

        $response = json_decode((string) $response->getBody(), true);

        APILogger::addInfo('Retrieved hold request by id', $response['data']);

        return new HoldRequest($response['data']);
    }
}