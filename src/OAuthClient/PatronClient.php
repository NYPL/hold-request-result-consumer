<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\HoldRequestResultConsumer\Model\Exception\NotRetryableException;
use NYPL\HoldRequestResultConsumer\Model\Exception\RetryableException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;
use NYPL\Starter\Model\Response\ErrorResponse;

class PatronClient extends APIClient
{
    /**
     * @param string $patronId
     * @return null|Patron
     * @throws NotRetryableException
     * @throws RetryableException
     */
    public static function getPatronById($patronId = '')
    {
        $url = Config::get('API_PATRON_URL') . '/' . $patronId;

        APILogger::addDebug('Retrieving patron by id', (array) $url);

        try {
            $response = self::get($url);

            $statusCode = $response->getStatusCode();

            $response = json_decode((string) $response->getBody(), true);

            APILogger::addDebug(
                'Retrieved patron by id',
                $response['data']
            );

            // Check statusCode range
            if ($statusCode === 200) {
                return new Patron($response['data']);
            } else {
                APILogger::addError(
                    'Failed',
                    array('Failed to retrieve patron ', $patronId, $response['type'], $response['message'])
                );
                return null;
            }
        } catch (ServerException $exception) {
            throw new RetryableException(
                'Server Error',
                'getPatronById met a server error',
                $exception->getResponse()->getStatusCode(),
                null,
                $exception->getResponse()->getStatusCode(),
                new ErrorResponse(
                    $exception->getResponse()->getStatusCode(),
                    'internal-server-error',
                    'getPatronById met a server error'
                )
            );
        } catch (ClientException $exception) {
            throw new NotRetryableException(
                'Client Error',
                'getPatronById met a client error',
                $exception->getResponse()->getStatusCode(),
                null,
                $exception->getResponse()->getStatusCode(),
                new ErrorResponse(
                    $exception->getResponse()->getStatusCode(),
                    'client-error',
                    'getPatronById met a client error'
                )
            );
        }
    }
}
