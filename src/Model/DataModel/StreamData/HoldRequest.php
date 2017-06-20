<?php

namespace NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;

/**
 * Class HoldRequest
 * @package NYPL\Services\Model\DataModel\StreamData
 */
class HoldRequest extends StreamData
{
    /**
     * Id associated with a processed hold request.
     *
     * @var int
     */
    public $id;

    /**
     * Tracking Id associated with a processed hold request.
     *
     * @var string
     */
    public $jobId = '';

    /**
     * Id of patron who put in a hold request.
     *
     * @var string
     */
    public $patron = '';

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
    public $updatedDate = '';

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
    public $record = '';

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
     * @var int
     */
    public $numberOfCopies;

    /**
     * Delivery location of hold request.
     *
     * @var string
     */
    public $deliveryLocation = '';

    /**
     * Document delivery data represented in an Electronic Document Delivery (EDD).
     *
     * @var DocDeliveryData
     */
    public $docDeliveryData;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getJobId(): string
    {
        return $this->jobId;
    }

    /**
     * @param string $jobId
     */
    public function setJobId(string $jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * @return string
     */
    public function getPatron(): string
    {
        return $this->patron;
    }

    /**
     * @param string $patron
     */
    public function setPatron(string $patron)
    {
        $this->patron = $patron;
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
    public function getUpdatedDate(): string
    {
        return $this->updatedDate;
    }

    /**
     * @param string $updatedDate
     */
    public function setUpdatedDate(string $updatedDate)
    {
        $this->updatedDate = $updatedDate;
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
    public function getRecord(): string
    {
        return $this->record;
    }

    /**
     * @param string $record
     */
    public function setRecord(string $record)
    {
        $this->record = $record;
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
     * @return int
     */
    public function getNumberOfCopies(): int
    {
        return $this->numberOfCopies;
    }

    /**
     * @param int $numberOfCopies
     */
    public function setNumberOfCopies(int $numberOfCopies)
    {
        $this->numberOfCopies = $numberOfCopies;
    }

    /**
     * @return string
     */
    public function getDeliveryLocation(): string
    {
        return $this->deliveryLocation;
    }

    /**
     * @param string $deliveryLocation
     */
    public function setDeliveryLocation(string $deliveryLocation)
    {
        $this->deliveryLocation = $deliveryLocation;
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