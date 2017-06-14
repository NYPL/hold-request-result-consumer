<?php
namespace NYPL\Services\Model\DataModel\StreamData;

use NYPL\Services\Model\DataModel\StreamData;

/**
 * Class ProcessedHoldRequest
 * @package NYPL\Services\Model\DataModel\StreamData
 */
class ProcessedHoldRequest extends StreamData
{
    /**
     * Id associated with a processed hold request.
     *
     * @var string
     */
    public $requestId = '';

    /**
     * Tracking Id associated with a processed hold request.
     *
     * @var string
     */
    public $trackingId = '';

    /**
     * Id of patron who put in a hold request.
     *
     * @var string
     */
    public $patronId = '';

    /**
     * Source according to NYPL.
     *
     * @var string
     */
    public $nyplSource = '';

    /**
     * Type of request for a processed hold request.
     *
     * @var string
     */
    public $requestType = '';

    /**
     * Date processed hold request created.
     *
     * @var string
     */
    public $createdDate = '';

    /**
     * Date processed hold request updated.
     *
     * @var string
     */
    public $updateDate = '';

    /**
     * @var bool
     */
    public $success = false;

    /**
     * @var bool
     */
    public $processed = false;

    /**
     * Type of record requested in a hold request.
     *
     * @var string
     */
    public $recordType = '';

    /**
     * Record number requested in a hold request.
     *
     * @var string
     */
    public $recordNumber = '';

    /**
     * Pickup location assigned in a hold request.
     *
     * @var string
     */
    public $pickupLocation = '';

    /**
     * The Need By date of a hold request.
     *
     * @var string
     */
    public $neededBy = '';

    /**
     * Number of copies specified for Electronic Document Delivery (EDD).
     *
     * @var string
     */
    public $numberOfCopies = '';

    /**
     * Document delivery data represented in an Electronic Document Delivery (EDD).
     *
     * @var DocDeliveryData
     */
    public $docDeliveryData;

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

    /**
     * @return string
     */
    public function getRequestType(): string
    {
        return $this->requestType;
    }

    /**
     * @param string $requestType
     */
    public function setRequestType(string $requestType)
    {
        $this->requestType = $requestType;
    }

    /**
     * @return string
     */
    public function getCreatedDate(): string
    {
        return $this->createdDate;
    }

    /**
     * @param string $createdDate
     */
    public function setCreatedDate(string $createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return string
     */
    public function getUpdateDate(): string
    {
        return $this->updateDate;
    }

    /**
     * @param string $updateDate
     */
    public function setUpdateDate(string $updateDate)
    {
        $this->updateDate = $updateDate;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success)
    {
        $this->success = $success;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->processed;
    }

    /**
     * @param bool $processed
     */
    public function setProcessed(bool $processed)
    {
        $this->processed = $processed;
    }

    /**
     * @return string
     */
    public function getRecordType(): string
    {
        return $this->recordType;
    }

    /**
     * @param string $recordType
     */
    public function setRecordType(string $recordType)
    {
        $this->recordType = $recordType;
    }

    /**
     * @return string
     */
    public function getRecordNumber(): string
    {
        return $this->recordNumber;
    }

    /**
     * @param string $recordNumber
     */
    public function setRecordNumber(string $recordNumber)
    {
        $this->recordNumber = $recordNumber;
    }

    /**
     * @return string
     */
    public function getPickupLocation(): string
    {
        return $this->pickupLocation;
    }

    /**
     * @param string $pickupLocation
     */
    public function setPickupLocation(string $pickupLocation)
    {
        $this->pickupLocation = $pickupLocation;
    }

    /**
     * @return string
     */
    public function getNeededBy(): string
    {
        return $this->neededBy;
    }

    /**
     * @param string $neededBy
     */
    public function setNeededBy(string $neededBy)
    {
        $this->neededBy = $neededBy;
    }

    /**
     * @return string
     */
    public function getNumberOfCopies(): string
    {
        return $this->numberOfCopies;
    }

    /**
     * @param string $numberOfCopies
     */
    public function setNumberOfCopies(string $numberOfCopies)
    {
        $this->numberOfCopies = $numberOfCopies;
    }

    /**
     * @return DocDeliveryData
     */
    public function getDocDeliveryData(): DocDeliveryData
    {
        return $this->docDeliveryData;
    }

    /**
     * @param DocDeliveryData $docDeliveryData
     */
    public function setDocDeliveryData(DocDeliveryData $docDeliveryData)
    {
        $this->docDeliveryData = $docDeliveryData;
    }

    /**
     * Includes DocDeliveryData object in a processed hold request.
     *
     * @param $data
     * @return DocDeliveryData
     */
    public function translateDocDeliveryData($data) {
        return new DocDeliveryData($data, true);
    }

}