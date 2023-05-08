<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class SierraAPIClient extends APIClient {

    protected static function getOptions(array $options = [])
    {
        APILogger::addInfo('Using SierraAPIClient#getOptions to build auth');
        $options['headers']['Authorization'] = 'Bearer ' . OAuthClient::getAccessToken('SIERRA_');
        APILogger::addInfo('Built Auth: ' . $options['headers']['Authorization']);
        $options['headers']['Content-type'] = 'application/json';
    }

    /**
     * @param string $uri
     * @param array $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function get($uri = '', array $options = [])
    {
        APILogger::addInfo('Fetching: ' . $uri);

        return self::getClient()->get(
            $uri,
            self::getOptions($options)
        );
    }

    public static function getPatronHolds($patronId)
    {
        APILogger::addInfo('Using SIERRA_API_BASE_URL: ' . Config::get('SIERRA_API_BASE_URL'));
        $url = Config::get('SIERRA_API_BASE_URL') . "/patrons/$patronId/holds?fields=default,note";

        APILogger::addInfo('Retrieving patron holds', $patronId);

        $response = self::get($url);
        // $response = ClientHelper::getResponse($url, __FUNCTION__);

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
