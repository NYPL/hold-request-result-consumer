<?php
namespace NYPL\Services;

use NYPL\Services\Model\DataModel\StreamData;
use NYPL\Services\Model\DataModel\StreamData\Patron;
use NYPL\Services\Model\DataModel\StreamData\DocDeliveryData;
use NYPL\Services\Model\Email\DeliveryEmail;
use NYPL\Services\Model\Email\PatronEmail;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;
use SendGrid\Content;
use SendGrid\Email;
use SendGrid\Mail;
use SendGrid\Response;

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

        if ($streamData instanceof Patron) {
            $email = new PatronEmail($streamData);
        }

        if ($streamData instanceof StreamData\HoldRequestResult) {
            $email = new DeliveryEmail($streamData);
        }

        if (!isset($email)) {
            throw new \Exception('Email was not specified');
        }

        APILogger::addInfo('Sending email to: ' . $email->getToAddress());

        $mail = new Mail(
            new Email(null, $email->getFromAddress()),
            $email->getSubject(),
            new Email(null, $email->getToAddress()),
            new Content('text/html', $email->getBody())
        );

        $sendGrid = new \SendGrid(
            Config::get('SENDGRID_API_KEY', null, true)
        );

        /**
         * @var Response $response
         */
        $response = $sendGrid->client->mail()->send()->post($mail);

        if ($response->statusCode() >= 400) {
            throw new APIException('Error sending mail: ' . $response->body());
        }
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

}
