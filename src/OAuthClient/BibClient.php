<?php

namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class BibClient extends APIClient
{
    /**
     * @param array $bibIds
     * @return array|null
     */
    public static function getBibsByIds(array $bibIds)
    {
        $bibIdList = implode(",", $bibIds);

        if ($bibIdList == '') {
            APILogger::addError(
                'Failed',
                array('No bibIds provided to ' . __FUNCTION__)
            );
            return null;
        }

        $url = Config::get('API_BIB_URL') . '?id=' . $bibIdList;

        APILogger::addDebug('Retrieving bib by Id and Source', (array)$url);

        $response = ClientHelper::getResponse($url, __FUNCTION__);

        $statusCode = $response->getStatusCode();

        $response = json_decode((string)$response->getBody(), true);

        APILogger::addDebug(
            'Retrieved bibs by ids',
            $response['data']
        );

        // Check statusCode range
        if ($statusCode === 200) {
            return $response['data'];
        } else {
            APILogger::addError(
                'Failed',
                array('Failed to retrieve bib(s) ', $bibIds, $response['type'], $response['message'])
            );
            return null;
        }
    }
}
