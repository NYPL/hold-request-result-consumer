<?php
namespace NYPL\Services\Model\DataModel\StreamData;

use NYPL\Services\Model\DataModel\StreamData;

/**
 * Class HoldRequestResult
 * @package NYPL\Services\Model\DataModel\StreamData
 */
class HoldRequestResult extends StreamData
{
    /**
     * @var string
     */
    public $jobId = '';

    /**
     * @var bool
     */
    public $success;

    /**
     * @var string
     */
    public $message = '';

    /**
     * @var HoldRequest
     */
    public $holdRequest;

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
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return HoldRequest
     */
    public function getHoldRequest(): HoldRequest
    {
        return $this->holdRequest;
    }

    /**
     * @param HoldRequest $holdRequest
     */
    public function setHoldRequest(HoldRequest $holdRequest)
    {
        $this->holdRequest = $holdRequest;
    }

    /**
     * @param $data
     * @return HoldRequest
     */
    public function translateHoldRequest($data) {
        return new HoldRequest($data, true);
    }
}