<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use NYPL\HoldRequestResultConsumer\Model\Exception\NonRetryableException;
use NYPL\HoldRequestResultConsumer\Model\Exception\RetryableException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;
use NYPL\Starter\Model\Response\ErrorResponse;

class BibClient extends APIClient
{
    /**
     * @param string $bibId
     * @param $nyplSource
     * @return null|Bib
     * @throws NonRetryableException
     * @throws RetryableException
     */
    public static function getBibByIdAndSource($bibId = '', $nyplSource)
    {
        $url = Config::get('API_BIB_URL') . '/'. $nyplSource . '/' . $bibId;

        APILogger::addDebug('Retrieving bib by Id and Source', (array) $url);

        $response = ClientHelper::getResponse($url, __FUNCTION__);

        $statusCode = $response->getStatusCode();

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
