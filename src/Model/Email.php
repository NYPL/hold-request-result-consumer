<?php
namespace NYPL\Services\Model;

use NYPL\Services\Model\DataModel\StreamData;

abstract class Email
{
    abstract public function getSubject();

    abstract public function getFromAddress();

    abstract public function getToAddress();

    abstract public function getTemplate();

    /**
     * @var \Twig_Environment
     */
    public static $twig;

    /**
     * @var StreamData
     */
    public $streamData;

    /**
     * @param StreamData $streamData
     */
    public function __construct(StreamData $streamData)
    {
        $this->setStreamData($streamData);
    }

    /**
     * @return \Twig_Environment
     */
    public static function getTwig()
    {
        if (!self::$twig) {
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../emails');

            $twig = new \Twig_Environment($loader);

            self::setTwig($twig);
        }

        return self::$twig;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return self::getTwig()->render($this->getTemplate(), ['data' => $this->getStreamData()]);
    }

    /**
     * @param \Twig_Environment $twig
     */
    public static function setTwig($twig)
    {
        self::$twig = $twig;
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
