<?php
namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use PHPUnit\Framework\TestCase;

class HoldRequestTest extends TestCase
{
    public $fakeHoldRequest;

    public function setUp()
    {
        $this->fakeHoldRequest = new class extends HoldRequest
        {
            public function __construct($data = [
                'id' => 0,
                'jobId' => '',
                'patron' => '',
                'nyplSource' => '',
                'requestType' => '',
                'createDate' => '',
                'updateDate' => '',
                'success' => false,
                'processed' => false,
                'recordType' => '',

            ])
            {
                parent::__construct($data);
            }
        };
        parent::setUp();
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testId()
    {
        $this->assertEquals(0, $this->fakeHoldRequest->getId());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testJobId()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getJobId());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testPatron()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getPatron());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testNyplSource()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getNyplSource());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testRequestType()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getRequestType());
    }

    public function testCreatedDate()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getCreatedDate());
    }

    
}
