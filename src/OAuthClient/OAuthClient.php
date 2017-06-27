<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use NYPL\Starter\Config;

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
            'clientId' => Config::get('OAUTH_CLIENT_ID'),
            'clientSecret' => Config::get('OAUTH_CLIENT_SECRET', null, true),
            'redirectUri' => '',
            'urlAuthorize' => Config::get('OAUTH_AUTH_URI'),
            'urlAccessToken' => Config::get('OAUTH_TOKEN_URI'),
            'urlResourceOwnerDetails' => '',
            'scopes' => 'read:patron read:item read:bib readwrite:holdrequest'
        ]);

        return $provider->getAccessToken('client_credentials');
    }
}
