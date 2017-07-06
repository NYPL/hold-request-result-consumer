<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;
use NYPL\Starter\Model\Response\ErrorResponse;

class HoldRequestClient extends APIClient
{
    /**
     * @param string $holdRequestId
     * @return HoldRequest
     */
    public static function getHoldRequestById($holdRequestId = '')
    {

        if (!isset($holdRequestId) || $holdRequestId === '') {
            throw new APIException(
                'No Hold Request Id.',
                'No Hold Request Id provided.',
                0,
                null,
                500,
                new ErrorResponse(500, 'no-hold-request-id', 'No Hold Request Id provided.')
            );
        } else {
            $url = Config::get('API_HOLD_REQUEST_URL') . '/' . $holdRequestId;

            APILogger::addInfo('Retrieving hold request by id', $url);

            $response = self::get($url);

            $response = json_decode((string)$response->getBody(), true);

            APILogger::addInfo('Retrieved hold request by id', $response['data']);

            return new HoldRequest($response['data']);
        }
    }

    /**
     * @param string $holdRequestId
     * @param bool $processed
     * @param bool $success
     * @return HoldRequest
     * @throws APIException
     */
    public static function patchHoldRequestById($holdRequestId = '', bool $processed, bool $success)
    {
        if (!isset($holdRequestId) || $holdRequestId === '') {
            throw new APIException(
                'No Hold Request Id.',
                'No Hold Request Id provided.',
                0,
                null,
                500,
                new ErrorResponse(500, 'no-hold-request-id', 'No Hold Request Id provided.')
            );
        } elseif (!isset($processed)) {
            throw new APIException(
                'Processed flag not set',
                'Processed flag is not set.',
                0,
                null,
                500,
                new ErrorResponse(500, 'processed-flag-not-set', 'Processed flag is not set.')
            );
        } elseif (!isset($success)) {
            throw new APIException(
                'Success flag not set',
                'Success flag is not set.',
                0,
                null,
                500,
                new ErrorResponse(500, 'success-flag-not-set', 'Success flag is not set.')
            );
        } else {
            $url = Config::get('API_HOLD_REQUEST_URL') . '/' . $holdRequestId;

            APILogger::addInfo('Patching hold request by id', $url);

            $body = ["processed" => $processed, "success" => $success];

            $response = self::patch($url, ["body" => json_encode($body)]);

            $response = json_decode((string)$response->getBody(), true);

            APILogger::addInfo('Patched hold request by id', $response['data']);

            return new HoldRequest($response['data']);
        }
    }
}
