<?php

namespace NYPL\HoldRequestResultConsumer\Test\Mocks;

use Dotenv\Dotenv;
use Aws\Kms\KmsClient;
use NYPL\Starter\Config;

class MockConfig extends Config
{
    const LOCAL_ENVIRONMENT_FILE = '.env';
    const GLOBAL_ENVIRONMENT_FILE = 'var_app';
    const DEFAULT_TIME_ZONE = 'America/New_York';

    protected static $initialized = false;

    protected static $configDirectory = '';

    protected static $required =
        [
            'AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY'
        ];

    protected static $addedRequired = [];

    /**
     * @var KmsClient
     */
    protected static $keyClient;

    /**
     * @param string $configDirectory
     * @param array $required
     */
    public static function initialize($configDirectory = '', array $required = [])
    {
        self::setConfigDirectory($configDirectory);

        if ($required) {
            self::setAddedRequired($required);
        }

        self::loadConfiguration();

        self::setInitialized(true);

        date_default_timezone_set(
            Config::get('TIME_ZONE', self::DEFAULT_TIME_ZONE)
        );
    }

    protected static function loadConfiguration()
    {
        $dotEnv = new Dotenv(self::getConfigDirectory(), self::LOCAL_ENVIRONMENT_FILE);
        $dotEnv->load();

        if (file_exists(self::getConfigDirectory() . '/' . self::LOCAL_ENVIRONMENT_FILE)) {
            $dotEnv = new Dotenv(self::getConfigDirectory(), self::LOCAL_ENVIRONMENT_FILE);
            $dotEnv->load();
        }

        if (file_exists(self::getConfigDirectory() . '/config/' . self::GLOBAL_ENVIRONMENT_FILE)) {
            $dotEnv = new Dotenv(self::getConfigDirectory() . '/config', self::GLOBAL_ENVIRONMENT_FILE);
            $dotEnv->load();
        }

        $dotEnv->required(self::getRequired());

        $dotEnv->required(self::getAddedRequired());

        self::setInitialized(true);
    }
}
