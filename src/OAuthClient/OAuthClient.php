<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use NYPL\Starter\Config;
use NYPL\Starter\APILogger;

class OAuthClient
{
    /**
     * @var string
     */
    public static $accessToken = array(); // '';

    /**
     * @return string
     */
    public static function getAccessToken($configPrefix = '')
    {
        // APILogger::addInfo("OauthClient::getAccessToken('$configPrefix')");
        if (!array_key_exists($configPrefix, self::$accessToken)) {
            // APILogger::addInfo("OauthClient::getAccessToken('$configPrefix') not yet built");
            $accessToken = self::initializeAccessToken($configPrefix);
            // APILogger::addInfo("Got access token: '{$accessToken->getToken()}'");

            self::setAccessToken($accessToken->getToken(), $configPrefix);
        }

        return self::$accessToken[$configPrefix];
    }

    /**
     * @param string $accessToken
     */
    public static function setAccessToken($accessToken, $configPrefix)
    {
        self::$accessToken[$configPrefix] = $accessToken;
    }

    protected static function initializeAccessToken($configPrefix = ''): AccessToken
    {

        // APILogger::addInfo("Building provider for $configPrefix");
        $provider = new GenericProvider([
            'clientId' => Config::get($configPrefix . 'OAUTH_CLIENT_ID', null, true),
            'clientSecret' => Config::get($configPrefix . 'OAUTH_CLIENT_SECRET', null, true),
            'redirectUri' => '',
            'urlAuthorize' => Config::get($configPrefix . 'OAUTH_AUTH_URI'),
            'urlAccessToken' => Config::get($configPrefix . 'OAUTH_TOKEN_URI'),
            'urlResourceOwnerDetails' => '',
            'scopes' => 'read:patron read:item read:bib readwrite:hold_request'
        ]);
        // APILogger::addInfo("Built provider for $configPrefix");

        return $provider->getAccessToken('client_credentials');
    }
}
