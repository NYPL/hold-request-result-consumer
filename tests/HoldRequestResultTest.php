<?php

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult;
use PHPUnit\Framework\TestCase;

class HoldRequestResultTest extends TestCase
{
    public $fakeHoldRequestResult;

    public function setUp()
    {
        $this->fakeHoldRequestResult = new class extends HoldRequestResult {
            public function __construct($data = ['jobId' => 'Test jobId',
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
}
