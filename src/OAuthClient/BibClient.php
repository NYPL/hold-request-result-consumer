<?php

namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use NYPL\HoldRequestResultConsumer\Model\Exception\NotRetryableException;
use NYPL\HoldRequestResultConsumer\Model\Exception\RetryableException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class BibClient extends APIClient
{
    /**
     * @param string $bibId
     * @param $nyplSource
     * @return null|Bib
     * @throws NotRetryableException
     * @throws RetryableException
     */
    public static function getBibByIdAndSource($bibId = '', $nyplSource)
    {
        $url = Config::get('API_BIB_URL') . '/' . $nyplSource . '/' . $bibId;

        APILogger::addDebug('Retrieving bib by Id and Source', (array)$url);

        $response = ClientHelper::getResponse($url, __FUNCTION__);

        $response = json_decode((string)$response->getBody(), true);

        APILogger::addDebug(
            'Retrieved bib by id and source',
            $response['data']
        );

        // Check statusCode range
        if ($statusCode === 200) {
            return new Bib($response['data']);
        } else {
            APILogger::addError(
                'Failed',
                array('Failed to retrieve bib ', $bibId, $response['type'], $response['message'])
            );
            return null;
        }
    }
}
