<?php
namespace NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;

/**
 * Class DocDeliveryData
 * @package NYPL\Services\Model\DataModel\StreamData
 */
class DocDeliveryData extends StreamData
{
    /**
     * Patron's e-mail address for delivery.
     *
     * @var string
     */
    public $emailAddress = '';

    /**
     * Title of a chapter in the document delivery
     *
     * @var string
     */
    public $chapterTitle = '';

    /**
     * Issue number of requested document delivery.
     *
     * @var string
     */
    public $issue = '';

    /**
     * Volume number of requested document delivery.
     *
     * @var string
     */
    public $volume = '';

    /**
     * Starting page number of requested document delivery.
     *
     * @var string
     */
    public $startPage = '';

    /**
     * Ending page number of requested document delivery.
     *
     * @var string
     */
    public $endPage = '';

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getChapterTitle(): string
    {
        return $this->chapterTitle;
    }

    /**
     * @param string $chapterTitle
     */
    public function setChapterTitle(string $chapterTitle)
    {
        $this->chapterTitle = $chapterTitle;
    }

    /**
     * @return string
     */
    public function getIssue(): string
    {
        return $this->issue;
    }

    /**
     * @param string $issue
     */
    public function setIssue(string $issue)
    {
        $this->issue = $issue;
    }

    /**
     * @return string
     */
    public function getVolume(): string
    {
        return $this->volume;
    }

    /**
     * @param string $volume
     */
    public function setVolume(string $volume)
    {
        $this->volume = $volume;
    }

    /**
     * @return string
     */
    public function getStartPage(): string
    {
        return $this->startPage;
    }

    /**
     * @param string $startPage
     */
    public function setStartPage(string $startPage)
    {
        $this->startPage = $startPage;
    }

    /**
     * @return string
     */
    public function getEndPage(): string
    {
        return $this->endPage;
    }

    /**
     * @param string $endPage
     */
    public function setEndPage(string $endPage)
    {
        $this->endPage = $endPage;
    }
}