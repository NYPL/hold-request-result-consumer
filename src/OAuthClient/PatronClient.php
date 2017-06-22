<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class PatronClient extends APIClient
{
    /**
     * @param string $patronId
     *
     * @return Patron
     */
    public static function getPatronById($patronId = '')
    {
        $url = Config::get('API_BASE_URL') . '/patrons/' . $patronId;

        APILogger::addInfo('Retrieving patron by id', $url);

        $response = self::get($url);

        $response = json_decode((string) $response->getBody(), true);

        APILogger::addInfo(
            'Retrieved patron by id',
            $response['data']
        );

        return new Patron($response['data']);
    }
}
