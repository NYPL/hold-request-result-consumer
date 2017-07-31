<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class ItemClient extends APIClient
{

    /**
     * @param string $itemId
     * @param $nyplSource
     * @return null|Item
     */
    public static function getItemByIdAndSource($itemId = '', $nyplSource)
    {
        $url = Config::get('API_ITEM_URL') . '/' . $nyplSource . '/' . $itemId;

        APILogger::addDebug('Retrieving item by Id and Source', (array) $url);

        $response = ClientHelper::getResponse($url, __FUNCTION__);

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
    }
}
