<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\HoldRequestResultConsumer\Model\Exception\NotRetryableException;
use NYPL\HoldRequestResultConsumer\Model\Exception\RetryableException;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;
use NYPL\Starter\Model\Response\ErrorResponse;

class HoldRequestClient extends APIClient
{
    /**
     * @param $holdRequestId
     * @return bool
     * @throws APIException
     */
    public static function validateRequestId(int $holdRequestId)
    {
        if (!isset($holdRequestId) || !is_numeric($holdRequestId) || $holdRequestId < 1) {
            throw new NotRetryableException(
                'Not Acceptable: Invalid hold request id: ' . $holdRequestId,
                'Not Acceptable: Invalid hold request id: ' . $holdRequestId,
                406,
                null,
                406,
                new ErrorResponse(406, 'invalid-hold-request-id', 'Invalid hold request id: ' . $holdRequestId)
            );
        }

        return true;
    }

    /**
     * @param $processed
     * @return bool
     * @throws APIException
     */
    public static function validateProcessed($processed)
    {
        if (!isset($processed)) {
            throw new NotRetryableException(
                'Not Acceptable: Processed flag not set',
                'Not Acceptable: Processed flag is not set.',
                406,
                null,
                406,
                new ErrorResponse(406, 'processed-flag-not-set', '406 Not Acceptable: Processed flag is not set.')
            );
        }

        return true;
    }

    /**
     * @param $success
     * @return bool
     * @throws APIException
     */
    public static function validateSuccess($success)
    {
        if (!isset($success)) {
            throw new NotRetryableException(
                'Not Acceptable: Success flag not set',
                'Not Acceptable: Success flag is not set.',
                406,
                null,
                406,
                new ErrorResponse(406, 'success-flag-not-set', '406 Not Acceptable: Success flag is not set.')
            );
        }

        return true;
    }

    /**
     * @param  $holdRequestId
     * @return HoldRequest
     * @throws APIException
     */
    public static function getHoldRequestById($holdRequestId)
    {
        self::validateRequestId($holdRequestId);

        $url = Config::get('API_HOLD_REQUEST_URL') . '/' . $holdRequestId;

        APILogger::addDebug('Retrieving hold request by id', (array) $url);

        $response = self::get($url);

        $statusCode = $response->getStatusCode();

        $response = json_decode((string)$response->getBody(), true);

        APILogger::addDebug('Retrieved hold request by id', $response['data']);

        // Check statusCode range
        if ($statusCode === 200) {
            return new HoldRequest($response['data']);
        } elseif ($statusCode >= 500 && $statusCode <= 599) {
            throw new RetryableException(
                'Server Error',
                'getHoldRequestById met a server error',
                $statusCode,
                null,
                $statusCode,
                new ErrorResponse(
                    $statusCode,
                    'internal-server-error',
                    'getHoldRequestById met a server error'
                )
            );
        } else {
            APILogger::addError(
                'Failed',
                array('Failed to retrieve Hold Request ', $holdRequestId, $response['type'], $response['message'])
            );
            return null;
        }
    }

    /**
     * @param string $holdRequestId
     * @param bool $processed
     * @param bool $success
     * @return null|HoldRequest
     * @throws RetryableException
     */
    public static function patchHoldRequestById($holdRequestId = '', bool $processed, bool $success)
    {
        self::validateRequestId($holdRequestId);
        self::validateProcessed($processed);
        self::validateSuccess($success);

        $url = Config::get('API_HOLD_REQUEST_URL') . '/' . $holdRequestId;

        APILogger::addDebug('Patching hold request by id', (array) $url);

        $body = ["processed" => $processed, "success" => $success];

        $response = self::patch($url, ["body" => json_encode($body)]);

        $statusCode = $response['statusCode'];

        $response = json_decode((string)$response->getBody(), true);

        APILogger::addDebug('Patched hold request by id', $response['data']);

        // Check statusCode range
        if ($response['statusCode'] === 200) {
            return new HoldRequest($response['data']);
        } elseif ($statusCode >= 500 && $statusCode <= 599) {
            throw new RetryableException(
                'Server Error',
                'patchHoldRequestById met a server error',
                $response['statusCode'],
                null,
                $response['statusCode'],
                new ErrorResponse(
                    $response['statusCode'],
                    'internal-server-error',
                    'patchHoldRequestById met a server error'
                )
            );
        } else {
            APILogger::addError(
                'Failed',
                array('Failed to retrieve Hold Request ', $holdRequestId, $response['type'], $response['message'])
            );
            return null;
        }
    }
}
