<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class BibClient extends APIClient
{
    /**
     * @param string $bibId
     * @param $nyplSource
     * @return Bib | null
     */
    public static function getBibByIdAndSource($bibId = '', $nyplSource)
    {
        $url = Config::get('API_BIB_URL') . '/'. $nyplSource . '/' . $bibId;

        APILogger::addDebug('Retrieving bib by Id and Source', (array) $url);

        $response = self::get($url);

        $response = json_decode((string) $response->getBody(), true);

        APILogger::addDebug(
            'Retrieved bib by id and source',
            $response['data']
        );

        if ($response['statusCode'] !== 200) {
            APILogger::addError(
                'Failed',
                array('Failed to retrieve bib ', $bibId, $response['type'], $response['message'])
            );
            return null;
        }

        return new Bib($response['data']);
    }
}
