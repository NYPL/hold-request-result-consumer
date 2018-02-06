<?php

namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class LocationClient extends APIClient
{
    /**
     * @param string $locationCode
     * @return string
     */
    public static function getRecapLocationById($locationCode = '')
    {
        $url = Config::get('API_RECAP_LOCATION_URL', null, true);

        APILogger::addDebug('Retrieving Recap location', (array) $url);

        $data = file_get_contents($url);

        $json = json_decode($data, true);

        foreach ($json as $key => $value) {
            if ($key === $locationCode) {
                return $value['label'];
            }
        }

        return '';
    }

    /**
     * @param string $locationCode
     * @return string
     */
    public static function getSierraLocationById($locationCode = '')
    {
        $url = Config::get('API_SIERRA_LOCATION_URL', null, true);

        APILogger::addDebug('Retrieving Sierra location', (array) $url);

        $data = file_get_contents($url);

        $json = json_decode($data, true);

        foreach ($json as $key => $value) {
            if ($key === $locationCode) {
                return $value['label'];
            }
        }

        return '';
    }
}
