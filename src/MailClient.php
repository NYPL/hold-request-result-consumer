<?php
namespace NYPL\HoldRequestResultConsumer;

use Aws\Ses\SesClient;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\HoldRequestResultConsumer\Model\Email\DeliveryEmail;
use NYPL\HoldRequestResultConsumer\Model\Email\HoldFailureEmail;
use NYPL\HoldRequestResultConsumer\Model\Email\HoldSuccessEmail;
use NYPL\HoldRequestResultConsumer\Model\Email\PatronEmail;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class MailClient
{
    /**
     * @var \Twig_Environment
     */
    protected static $twig;

    /**
     * @var string
     */
    protected $streamName = '';

    /**
     * @var StreamData
     */
    protected $streamData;

    /**
     * @var SesClient
     */
    protected $client;

    /**
     * @param string $streamName
     * @param StreamData $streamData
     */
    public function __construct($streamName, StreamData $streamData)
    {
        $this->setStreamName($streamName);
        $this->setStreamData($streamData);
    }

    /**
     * @throws \Exception
     */
    public function sendEmail()
    {
        $streamData = $this->getStreamData();

        if ($streamData instanceof HoldEmailData) {
            if ($streamData->isSuccess() === true) {
                if ($streamData->getDocDeliveryData() !== null &&
                    $streamData->getDocDeliveryData()->getEmailAddress() !== null) {
                    $email = new DeliveryEmail($streamData);
                } else {
                    $email = new HoldSuccessEmail($streamData);
                }
            } else {
                $email = new HoldFailureEmail($streamData);
            }
        }

        if (!isset($email)) {
            throw new \Exception('Email was not specified');
        }

        try {
            $this->getClient()->sendEmail([
                'Destination' => [
                    'ToAddresses' => [
                        $email->getToAddress()
                    ],
                ],
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => 'utf-8',
                            'Data' => $email->getBody(),
                        ]
                    ],
                    'Subject' => [
                        'Charset' => 'utf-8',
                        'Data' => $email->getSubject(),
                    ],
                ],
                'Source' => $email->getFromAddress()
            ]);
        } catch (\Exception $exception) {
            throw new APIException('Error sending mail: ' . $exception->getMessage());
        }

        APILogger::addInfo('E-mail sent successfully');
    }

    /**
     * @return string
     */
    public function getStreamName()
    {
        return $this->streamName;
    }

    /**
     * @param string $streamName
     */
    public function setStreamName($streamName)
    {
        $this->streamName = $streamName;
    }

    /**
     * @return StreamData
     */
    public function getStreamData()
    {
        return $this->streamData;
    }

    /**
     * @param StreamData $streamData
     */
    public function setStreamData($streamData)
    {
        $this->streamData = $streamData;
    }

    /**
     * @throws APIException|\InvalidArgumentException
     * @return SesClient
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->setClient(
                new SesClient([
                    'version' => 'latest',
                    'region'  => Config::get('AWS_DEFAULT_REGION'),
                    'credentials' => [
                        'key' => Config::get('AWS_ACCESS_KEY_ID'),
                        'secret' => Config::get('AWS_SECRET_ACCESS_KEY'),
                        'token' => Config::get('AWS_SESSION_TOKEN')
                    ]
                ])
            );
        }

        return $this->client;
    }

    /**
     * @param SesClient $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}
