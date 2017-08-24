<?php

namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\Error;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult;
use PHPUnit\Framework\TestCase;

class HoldRequestResultTest extends TestCase
{
    public $fakeHoldRequestResult;
    public $fakeError;

    public function setUp()
    {
        $this->fakeError = new class extends Error
        {
            public function __construct($data = null, $decodeJson = false, $validateData = false)
            {
                parent::__construct($data, $decodeJson, $validateData);
            }
        };
        $this->fakeHoldRequestResult = new class extends HoldRequestResult
        {
            public function __construct($data = [
                'jobId' => 'Test jobId',
                'success' => false,
                'holdRequestId' => 0,
                'error' => null
            ])
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

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult
     */
    public function testHoldRequestId()
    {
        $this->assertEquals(0, $this->fakeHoldRequestResult->getHoldRequestId());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult
     */
    public function testError()
    {
        $this->fakeHoldRequestResult->setError($this->fakeError);
        $this->assertEquals($this->fakeError, $this->fakeHoldRequestResult->getError());
    }
}
