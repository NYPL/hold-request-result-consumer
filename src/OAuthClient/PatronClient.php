<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\HoldRequestResultConsumer\Model\Exception\RetryableException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;
use NYPL\Starter\Model\Response\ErrorResponse;

class PatronClient extends APIClient
{
    /**
     * @param string $patronId
     * @return null|Patron
     * @throws RetryableException
     */
    public static function getPatronById($patronId = '')
    {
        $url = Config::get('API_PATRON_URL') . '/' . $patronId;

        APILogger::addDebug('Retrieving patron by id', (array) $url);

        $response = ClientHelper::getResponse($url, __FUNCTION__);

        $statusCode = $response->getStatusCode();

        $response = json_decode((string) $response->getBody(), true);

        APILogger::addDebug(
            'Retrieved patron by id',
            $response['data']
        );

        // Check statusCode range
        if ($statusCode === 200) {
            return new Patron($response['data']);
        } elseif ($statusCode >= 500 && $statusCode <= 599) {
            throw new RetryableException(
                'Server Error',
                'getPatronById met a server error',
                $statusCode,
                null,
                $statusCode,
                new ErrorResponse(
                    $statusCode,
                    'internal-server-error',
                    'getPatronById met a server error'
                )
            );
        } else {
            APILogger::addError(
                'Failed',
                array('Failed to retrieve patron ', $patronId, $response['type'], $response['message'])
            );
            return null;
        }
    }
}
