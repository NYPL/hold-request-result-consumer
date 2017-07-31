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
     * @var string|null
     */
    public $author;

    /**
     * @var string|null
     */
    public $requestNotes;

    /**
     * Title of a chapter in the document delivery
     *
     * @var string
     */
    public $chapterTitle = '';

    /**
     * Issue number of requested document delivery.
     *
     * @var string|null
     */
    public $issue;

    /**
     * Volume number of requested document delivery.
     *
     * @var string|null
     */
    public $volume;

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
     * @return null|string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param null|string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return null|string
     */
    public function getRequestNotes()
    {
        return $this->requestNotes;
    }

    /**
     * @param null|string $requestNotes
     */
    public function setRequestNotes($requestNotes)
    {
        $this->requestNotes = $requestNotes;
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
     * @return string|null
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @param string|null $issue
     */
    public function setIssue($issue)
    {
        $this->issue = $issue;
    }

    /**
     * @return string|null
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param string|null $volume
     */
    public function setVolume($volume)
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
