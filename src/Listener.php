<?php
namespace NYPL\HoldRequestResultConsumer;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult;
use NYPL\HoldRequestResultConsumer\OAuthClient\BibClient;
use NYPL\HoldRequestResultConsumer\OAuthClient\HoldRequestClient;
use NYPL\HoldRequestResultConsumer\OAuthClient\ItemClient;
use NYPL\HoldRequestResultConsumer\OAuthClient\PatronClient;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;

class Listener
{
    /**
     * @var array
     */
    protected $records = [];

    /**
     * Listener constructor.
     */
    public function __construct()
    {
        set_error_handler(self::class . "::errorHandler");

        register_shutdown_function(self::class . '::fatalHandler');
    }

    public static function fatalHandler()
    {
        $error = error_get_last();

        if ($error !== null) {
            error_log(
                json_encode([
                    'message' => $error['message'],
                    'level' => 500,
                    'level_name' => 'ERROR'
                ])
            );
        }
    }

    /**
     * @param int $errorNumber
     * @param string $errorString
     * @param string $errorFile
     * @param string $errorLine
     * @param array $errorContext
     */
    public static function errorHandler($errorNumber = 0, $errorString = '', $errorFile = '', $errorLine = '', array $errorContext)
    {
        APILogger::addError(
            'Error ' . $errorNumber . ': ' . $errorString . ' in ' . $errorFile . ' on line ' . $errorLine,
            $errorContext
        );
    }

    /**
     * @return array
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * @param array $records
     */
    public function setRecords($records)
    {
        $this->records = $records;
    }

    protected function initializeRecords()
    {
        APILogger::addInfo('Decoding buffer using file_get_contents()');

        $buffer = json_decode(file_get_contents('php://stdin'), true);

        if (isset($buffer['Records'])) {
            $this->setRecords($buffer['Records']);
        }

        if (!$this->getRecords()) {
            APILogger::addError(
                'Error decoding buffer',
                ['json_error' => json_last_error(), 'buffer' => $buffer]
            );
        }
    }

    /**
     * @param string $streamArn
     *
     * @return string
     */
    protected function getStreamNameFromArn($streamArn = '')
    {
        $streamComponents = explode('/', $streamArn);

        $streamName = $streamComponents[count($streamComponents) - 1];

        APILogger::addInfo(
            'Processing record in ' .
            $streamName . ' stream.'
        );

        return $streamName;
    }

    /**
     * @param string $streamName
     *
     * @return string
     */
    protected function getSchemaNameFromStreamName($streamName = '')
    {
        $streamComponents = explode('-', $streamName);

        $schemaName = implode('-', array_slice($streamComponents, 0, count($streamComponents) - 1));

        return $schemaName;
    }

    public function process()
    {
        $this->initializeRecords();

        APILogger::addInfo('Processing ' . count($this->getRecords()) . ' record(s).');

        $addCount = 0;

        if ($this->getRecords()) {
            foreach ($this->getRecords() as $record) {
                try {
                    $streamName = $this->getStreamNameFromArn($record['eventSourceARN']);

                    $schemaName = $this->getSchemaNameFromStreamName($streamName);

                    $data = AvroDeserializer::deserializeWithSchema(
                        SchemaClient::getSchema($schemaName),
                        base64_decode($record['kinesis']['data'])
                    );

                    APILogger::addDebug('data', $data);

                    $holdRequestResult = new HoldRequestResult($data);

                    APILogger::addDebug('HoldRequestResult', (array) $holdRequestResult);


                    $holdRequest = HoldRequestClient::getHoldRequestById($holdRequestResult->getHoldRequestId());
                    APILogger::addDebug('HoldRequest', (array)$holdRequest);

                    $patron = PatronClient::getPatronById($holdRequest->getPatron());

                    if($holdRequest->getRecordType() === 'i') {
                        $item = ItemClient::getItemByIdAndSource($holdRequest->getRecord(), $holdRequest->getNyplSource());

                        APILogger::addDebug('Item', (array) $item );
                        APILogger::addDebug('BibIds', $item->getBibIds());

                        $bib = BibClient::getBibByIdAndSource($item->getBibIds()[0], $item->getNyplSource());

                        APILogger::addDebug('Bib', (array) $bib);

                        $holdEmailData = new HoldEmailData();
                        $holdEmailData->assembleData($patron, $bib, $item, $holdRequest, $holdRequestResult);

                        if ($holdEmailData->getPatronEmail() !== '') {
                            $mailClient = new MailClient($streamName, $holdEmailData);
                            $mailClient->sendEmail();
                        } else {
                            throw new APIException('No e-mail', array('Patron did not provide an e-mail address.'));
                        }
                    }

                    ++$addCount;
                } catch (\Exception $exception) {
                    APILogger::addError($exception->getMessage(), (array) $exception);
                }
            }
        }

        return $addCount;
    }
}
