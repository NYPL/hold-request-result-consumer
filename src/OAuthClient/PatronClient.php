<?php

namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class PatronClient extends APIClient
{
    /**
     * @param string $patronId
     * @return null|Patron
     */
    public static function getPatronById($patronId = '')
    {
        $url = Config::get('API_PATRON_URL') . '/' . $patronId;

        APILogger::addDebug('Retrieving patron by id', (array) $url);

        $response = ClientHelper::getResponse($url, __FUNCTION__);

        $statusCode = $response->getStatusCode();

        $response = json_decode((string) $response->getBody(), true);

        APILogger::addDebug(
            'Retrieved patron by id',
            $response['data']['id']
        );

        // Check statusCode range
        if ($statusCode === 200) {
            return new Patron($response['data']);
        } else {
            APILogger::addError(
                'Failed',
                array('Failed to retrieve patron ', $patronId, $response['type'], $response['message'])
            );
            return null;
        }
    }

    public static function notifyPatron(Patron $patron, int $holdId)
    {
        $url = Config::get('API_PATRON_URL') . '/' . $patron->getId() . '/notify';
        $payload = array('holdRequestServiceId' => $holdId, 'type' => 'hold-success');
        APILogger::addInfo("Calling PatronServices/notify: $url " . json_encode($payload));

        $response = ClientHelper::postResponse($url, $payload, __FUNCTION__);
        $statusCode = $response->getStatusCode();

        $response = json_decode((string)$response->getBody(), true);

        APILogger::addInfo('Got response from patronservices notify endpoint ' . json_encode($response));

        // Check statusCode range
        if ($statusCode === 200) {
          return true;
        } else {
            APILogger::addError(
                'Failed',
                array('Failed to call patronservices notify endpoint')
            );
            return null;
        }
    }
}
