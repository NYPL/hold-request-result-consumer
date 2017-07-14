<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;


use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class ItemClient extends APIClient
{
    /**
     * @param string $itemId
     * @param $nyplSource
     * @return Item
     */
    public static function getItemByIdAndSource($itemId = '', $nyplSource)
    {
        $url = Config::get('API_ITEM_URL') . '/' . $nyplSource . '/' . $itemId;

        APILogger::addDebug('Retrieving item by Id and Source', (array) $url);

        $response = self::get($url);

        $response = json_decode((string) $response->getBody(), true);

        APILogger::addDebug(
            'Retrieve item by id and source',
            $response['data']
        );

        if ($response['statusCode'] !== 200) {
            APILogger::addError('Failed', array('Failed to retrieve item ', $itemId));
            return null;
        }

        return new Item($response['data']);
    }
}
