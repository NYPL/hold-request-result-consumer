<?php
namespace NYPL\HoldRequestResultConsumer\Model\DataModel;

use NYPL\HoldRequestResultConsumer\Model\DataModel;

class Item extends DataModel
{
    /**
     * Id of item
     *
     * @var string
     */
    public $id = '';

    /**
     * NYPL Source of item
     *
     * @var string
     */
    public $nyplSource = '';

    /**
     * Bib ids associated with item
     *
     * @var array
     */
    public $bibIds = [];

    /**
     * NYPL Type of item
     *
     * @var string
     */
    public $nyplType = '';

    /**
     * @var string
     */
    public $barcode = '';

    /**
     * @var string
     */
    public $callNumber = '';

    /**
     * @var string
     */
    public $itemType = '';

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
     * @return array
     */
    public function getBibIds(): array
    {
        return $this->bibIds;
    }

    /**
     * @param array $bibIds
     */
    public function setBibIds(array $bibIds)
    {
        $this->bibIds = $bibIds;
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
    public function getBarcode(): string
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     */
    public function setBarcode(string $barcode)
    {
        $this->barcode = $barcode;
    }

    /**
     * @return string
     */
    public function getCallNumber(): string
    {
        return $this->callNumber;
    }

    /**
     * @param string $callNumber
     */
    public function setCallNumber(string $callNumber)
    {
        $this->callNumber = $callNumber;
    }

    /**
     * @return string
     */
    public function getItemType(): string
    {
        return $this->itemType;
    }

    /**
     * @param string $itemType
     */
    public function setItemType(string $itemType)
    {
        $this->itemType = $itemType;
    }
}
