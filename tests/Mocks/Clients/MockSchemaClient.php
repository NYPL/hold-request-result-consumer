<?php

namespace NYPL\HoldRequestResultConsumer\Test\Mocks\Clients;

use GuzzleHttp\Client;
use NYPL\Starter\APILogger;
use NYPL\Starter\AvroLoader;
use NYPL\Starter\Schema;

class MockSchemaClient
{
    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var array
     */
    protected static $schemaCache = [];

    /**
     * @return Client
     */
    public static function getClient()
    {
        if (!self::$client) {
            self::setClient(
                new Client()
            );
        }

        return self::$client;
    }

    /**
     * @param Client $client
     */
    public static function setClient($client)
    {
        self::$client= $client;
    }

    /**
     * @param string $schemaName
     *
     * @return Schema
     */
    public static function getSchema($schemaName = '')
    {
        if (isset(self::$schemaCache[$schemaName])) {
            return self::$schemaCache[$schemaName];
        }

        AvroLoader::load();

        $response = json_decode(
            file_get_contents(__DIR__ . '/../../data/test_schema.json'),
            true
        );

        $schema = new Schema(
            $schemaName,
            0,
            \AvroSchema::parse($response['data']['schema']),
            $response['data']['schemaObject']
        );

        self::$schemaCache[$schemaName] = $schema;

        APILogger::addDebug(
            'Got schema for ' . $schemaName,
            (array) $schema->getSchema()
        );

        return $schema;
    }
}
