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

    public static function notifyPatron(Patron $patron, int $holdId, $notificationType)
    {
        $url = Config::get('API_PATRON_URL') . '/' . $patron->getId() . '/notify';
        $payload = array('holdRequestServiceId' => $holdId, 'type' => $notificationType);
        APILogger::addInfo("Calling PatronServices/notify: $url " . json_encode($payload));

        $response = ClientHelper::postResponse($url, $payload, __FUNCTION__);
        $statusCode = $response->getStatusCode();

        $response = json_decode((string)$response->getBody(), true);

        $responseMessage = array_key_exists('Message', $response) ? $response['Message'] : json_encode($response);
        APILogger::addInfo("Got $statusCode response from patronservices notify endpoint ($responseMessage)");

        // Check statusCode range
        if ($statusCode === 200) {
          return true;
        } else {
            APILogger::addError(
                'Failed',
                array("PatronServices Notify endpoint responded with $statusCode ($responseMessage)")
            );
            return null;
        }
    }
}
