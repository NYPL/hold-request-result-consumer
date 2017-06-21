<?php

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequest;
use PHPUnit\Framework\TestCase;

class HoldRequestResultTest extends TestCase
{
    public $fakeHoldRequestResult;

    public function setUp()
    {
        $this->fakeHoldRequestResult = new class extends HoldRequestResult {
            public function __construct($data = [
                'jobId' => 'Test jobId',
                'success' => false,
                'message' => 'fakeHoldRequestResult'])
            {
                parent::__construct($data);
            }
        };
        parent::setUp();
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult
     */
    public function testJobId()
    {
        $this->assertEquals('Test jobId', $this->fakeHoldRequestResult->getJobId());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult
     */
    public function testSuccess()
    {
        $this->assertFalse($this->fakeHoldRequestResult->isSuccess());
    }
}
