<?php
namespace NYPL\Services\Model\DataModel\StreamData;

use NYPL\Services\Model\DataModel\StreamData;

class ProcessedHoldRequest extends StreamData
{
    public $requestId = '';

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * @param string $requestId
     */
    public function setRequestId(string $requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * @return string
     */
    public function getTrackingId(): string
    {
        return $this->trackingId;
    }

    /**
     * @param string $trackingId
     */
    public function setTrackingId(string $trackingId)
    {
        $this->trackingId = $trackingId;
    }

    /**
     * @return string
     */
    public function getPatronId(): string
    {
        return $this->patronId;
    }

    /**
     * @param string $patronId
     */
    public function setPatronId(string $patronId)
    {
        $this->patronId = $patronId;
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

    public $trackingId = '';

    public $patronId = '';

    public $nyplSource = '';

}