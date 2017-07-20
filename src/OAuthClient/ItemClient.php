<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;


use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
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
     * @throws RetryableException
     */
    public static function getItemByIdAndSource($itemId = '', $nyplSource)
    {
        $url = Config::get('API_ITEM_URL') . '/' . $nyplSource . '/' . $itemId;

        APILogger::addDebug('Retrieving item by Id and Source', (array) $url);

        $response = self::get($url);

        $statusCode = $response->getStatusCode();

        $response = json_decode((string) $response->getBody(), true);

        APILogger::addDebug(
            'Retrieve item by id and source',
            $response['data']
        );

        // Check statusCode range
        if ($statusCode === 200) {
            return new Item($response['data']);
        } elseif ($statusCode >= 500 && $statusCode <= 599) {
            throw new RetryableException(
                'Server Error',
                'getItemByIdAndSource met a server error',
                $statusCode,
                null,
                $statusCode,
                new ErrorResponse(
                    $statusCode,
                    'internal-server-error',
                    'getItemByIdAndSource met a server error'
                )
            );
        } else {
            APILogger::addError(
                'Failed',
                array('Failed to retrieve item ', $itemId, $response['type'], $response['message'])
            );
            return null;
        }
    }
}
