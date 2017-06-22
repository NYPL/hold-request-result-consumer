<?php
namespace NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;

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
     * @var int
     */
    public $holdRequestId;

    /**
     * @var Error
     */
    public $error;

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
     * @return int
     */
    public function getHoldRequestId(): int
    {
        return $this->holdRequestId;
    }

    /**
     * @param int $holdRequestId
     */
    public function setHoldRequestId(int $holdRequestId)
    {
        $this->holdRequestId = $holdRequestId;
    }


    /**
     * @return Error
     */
    public function getError(): Error
    {
        return $this->error;
    }

    /**
     * @param Error $error
     */
    public function setError(Error $error)
    {
        $this->error = $error;
    }

    /**
     * @param $data
     * @return Error
     */
    public function translateError($data)
    {
        return new Error($data, true);
    }
}
