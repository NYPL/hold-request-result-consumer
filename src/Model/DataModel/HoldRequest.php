<?php

namespace NYPL\HoldRequestResultConsumer\Model\DataModel;

use NYPL\HoldRequestResultConsumer\Model\DataModel;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData;

/**
 * Class HoldRequest
 * @package NYPL\Services\Model\DataModel
 */
class HoldRequest extends DataModel
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
     * @var string | null
     */
    public $jobId;

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
     * @var string|null
     */
    public $requestType;

    /**
     * Date processed hold request created.
     *
     * @var string | null
     */
    public $createdDate;

    /**
     * Date processed hold request updated.
     *
     * @var string | null
     */
    public $updatedDate;

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
     * @var string | null
     */
    public $pickupLocation;

    /**
     * The Need By date of a hold request.
     *
     * @var string|null
     */
    public $neededBy;

    /**
     * Number of copies specified for Electronic Document Delivery (EDD).
     *
     * @var int|null
     */
    public $numberOfCopies;

    /**
     * Delivery location of hold request.
     *
     * @var string | null
     */
    public $deliveryLocation;

    /**
     * Document delivery data represented in an Electronic Document Delivery (EDD).
     *
     * @var DocDeliveryData | null
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
     * @return string | null
     */
    public function getJobId(): string
    {
        return $this->jobId;
    }

    /**
     * @param string $jobId
     */
    public function setJobId($jobId)
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
     * @return string|null
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * @param string|null $requestType
     */
    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;
    }

    /**
     * @return string | null
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param string|null $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return string | null
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @param string|null $updatedDate
     */
    public function setUpdatedDate($updatedDate)
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
     * @return string|null
     */
    public function getPickupLocation()
    {
        return $this->pickupLocation;
    }

    /**
     * @param string|null $pickupLocation
     */
    public function setPickupLocation($pickupLocation)
    {
        $this->pickupLocation = $pickupLocation;
    }

    /**
     * @return string|null
     */
    public function getNeededBy()
    {
        return $this->neededBy;
    }

    /**
     * @param string|null $neededBy
     */
    public function setNeededBy($neededBy)
    {
        $this->neededBy = $neededBy;
    }

    /**
     * @return int|null
     */
    public function getNumberOfCopies()
    {
        return $this->numberOfCopies;
    }

    /**
     * @param int|null $numberOfCopies
     */
    public function setNumberOfCopies($numberOfCopies)
    {
        $this->numberOfCopies = $numberOfCopies;
    }

    /**
     * @return string|null
     */
    public function getDeliveryLocation()
    {
        return $this->deliveryLocation;
    }

    /**
     * @param string $deliveryLocation
     */
    public function setDeliveryLocation($deliveryLocation)
    {
        $this->deliveryLocation = $deliveryLocation;
    }

    /**
     * @return DocDeliveryData | null
     */
    public function getDocDeliveryData()
    {
        return $this->docDeliveryData;
    }

    /**
     * @param DocDeliveryData | null $docDeliveryData
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
    public function translateDocDeliveryData($data)
    {
        return new DocDeliveryData($data, true);
    }
}
