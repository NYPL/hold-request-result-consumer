<?php
namespace NYPL\Services\OAuthClient;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use NYPL\Services\Config\Config;
use NYPL\Starter\APILogger;


class OAuthClient
{
    /**
     * @var string
     */
    public static $accessToken = '';

    /**
     * @return string
     */
    public static function getAccessToken()
    {
        if (!self::$accessToken) {
            $accessToken = self::initializeAccessToken();

            self::setAccessToken($accessToken->getToken());
        }

        APILogger::addInfo('Access Token', array(self::$accessToken));

        return self::$accessToken;
    }

    /**
     * @param string $accessToken
     */
    public static function setAccessToken($accessToken)
    {
        self::$accessToken = $accessToken;
    }

    protected static function initializeAccessToken(): AccessToken
    {
        $provider = new GenericProvider([
            'clientId' => Config::OAUTH_CLIENT_ID,
            'clientSecret' => Config::OAUTH_CLIENT_SECRET,
            'redirectUri' => '',
            'urlAuthorize' => Config::OAUTH_AUTH_URI,
            'urlAccessToken' => Config::OAUTH_TOKEN_URI,
            'urlResourceOwnerDetails' => ''
        ]);

        return $provider->getAccessToken('client_credentials');
    }

}