<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use NYPL\HoldRequestResultConsumer\Model\Exception\NonRetryableException;
use NYPL\HoldRequestResultConsumer\Model\Exception\RetryableException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;
use NYPL\Starter\Model\Response\ErrorResponse;

class ItemClient extends APIClient
{
    /**
     * @param string $itemId
     * @param $nyplSource
     * @return null|Item
     * @throws NonRetryableException
     * @throws RetryableException
     */
    public static function getItemByIdAndSource($itemId = '', $nyplSource)
    {
        $url = Config::get('API_ITEM_URL') . '/' . $nyplSource . '/' . $itemId;

        APILogger::addDebug('Retrieving item by Id and Source', (array) $url);

        try {
            $response = self::get($url);

            $statusCode = $response->getStatusCode();

            $response = json_decode((string)$response->getBody(), true);

            APILogger::addDebug(
                'Retrieve item by id and source',
                $response['data']
            );

            // Check statusCode range
            if ($statusCode === 200) {
                return new Item($response['data']);
            } else {
                APILogger::addError(
                    'Failed',
                    array('Failed to retrieve item ', $itemId, $response['type'], $response['message'])
                );
                return null;
            }
        } catch (ServerException $exception) {
            throw new RetryableException(
                'Server Error from ' . __FUNCTION__,
                'Server Error from ' . __FUNCTION__,
                $exception->getResponse()->getStatusCode(),
                null,
                $exception->getResponse()->getStatusCode(),
                new ErrorResponse(
                    $exception->getResponse()->getStatusCode(),
                    'internal-server-error',
                    'Server Error from ' . __FUNCTION__
                )
            );
        } catch (ClientException $exception) {
            throw new NonRetryableException(
                'Client Error from '. __FUNCTION__,
                'Client Error from '. __FUNCTION__,
                $exception->getResponse()->getStatusCode(),
                null,
                $exception->getResponse()->getStatusCode(),
                new ErrorResponse(
                    $exception->getResponse()->getStatusCode(),
                    'client-error',
                    'Client Error from '. __FUNCTION__
                )
            );
        }
    }
}
