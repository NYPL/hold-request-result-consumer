<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class HoldRequestClient extends APIClient
{
    /**
     * @param string $holdRequestId
     * @return HoldRequest
     */
    public static function getHoldRequestById($holdRequestId = '')
    {
        $url = Config::get('API_HOLD_REQUEST_URL') . '/' . $holdRequestId;

        APILogger::addInfo('Retrieving hold request by id', $url);

        $response = self::get($url);

        $response = json_decode((string) $response->getBody(), true);

        APILogger::addInfo('Retrieved hold request by id', $response['data']);

        return new HoldRequest($response['data']);
    }
}
