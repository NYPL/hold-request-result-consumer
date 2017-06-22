<?php
namespace NYPL\HoldRequestResultConsumer\Model\DataModel;

use NYPL\HoldRequestResultConsumer\Model\DataModel;

class Bib extends DataModel
{
    public $id = '';

    public $nyplSource = '';

    public $nyplType = '';

    public $title = '';

    public $author = '';

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNyplSource(): string
    {
        return $this->nyplSource;
    }

    /**
     * @param string $nyplSource
     */
    public function setNyplSource(string $nyplSource)
    {
        $this->nyplSource = $nyplSource;
    }

    /**
     * @return string
     */
    public function getNyplType(): string
    {
        return $this->nyplType;
    }

    /**
     * @param string $nyplType
     */
    public function setNyplType(string $nyplType)
    {
        $this->nyplType = $nyplType;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
    }
}