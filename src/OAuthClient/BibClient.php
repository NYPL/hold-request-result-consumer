<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class BibClient extends APIClient
{
    public static function getBibByIdAndSource($bibId = '', $nyplSource)
    {
        $url = Config::get('API_BASE_URL') . '/bibs/'. $nyplSource . '/' . $bibId;

        APILogger::addInfo('Retrieving bib by Id and Source', $url);

        $response = self::get($url);

        $response = json_decode((string) $response->getBody(), true);

        APILogger::addInfo(
            'Retrieved bib by id and source',
            $response['data']
        );

        return new Bib($response['data']);
    }
}