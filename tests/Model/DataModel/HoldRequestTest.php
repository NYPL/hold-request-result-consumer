<?php
namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData;
use PHPUnit\Framework\TestCase;

class HoldRequestTest extends TestCase
{
    public $fakeHoldRequest;

    public $fakeDocDeliveryData;

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
                'createdDate' => '',
                'updatedDate' => '',
                'success' => false,
                'processed' => false,
                'recordType' => '',
                'record' => '',
                'pickupLocation' => '',
                'neededBy' => '',
                'numberOfCopies' => 0,
                'deliveryLocation' => '',
                'docDeliveryData' => null
            ])
            {
                parent::__construct($data);
            }
        };

        $this->fakeDocDeliveryData = new class extends DocDeliveryData
        {
            public function __construct(
                $data = [
                    'emailAddress' => 'fake@example.com',
                    'author' => '',
                    'requestNotes' => '',
                    'chapterTitle' => '',
                    'issue' => '',
                    'volume' => '',
                    'startPage' => '',
                    'endPage' => ''
                ]
            ) {
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

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testCreatedDate()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getCreatedDate());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testUpdatedDate()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getUpdatedDate());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testSuccess()
    {
        $this->assertFalse($this->fakeHoldRequest->isSuccess());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testProcessed()
    {
        $this->assertFalse($this->fakeHoldRequest->isProcessed());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testRecordType()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getRecordType());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testRecord()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getRecord());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testPickupLocation()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getPickupLocation());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testNeededBy()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getNeededBy());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testNumberOfCopies()
    {
        $this->assertEquals(0, $this->fakeHoldRequest->getNumberOfCopies());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testDeliveryLocation()
    {
        $this->assertEquals('', $this->fakeHoldRequest->getDeliveryLocation());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testDocDeliveryDataCanBeNull()
    {
        $this->assertNull($this->fakeHoldRequest->getDocDeliveryData());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest
     */
    public function testDocDeliveryData()
    {
        $this->fakeHoldRequest->setDocDeliveryData($this->fakeDocDeliveryData);
        $this->assertEquals($this->fakeDocDeliveryData, $this->fakeHoldRequest->getDocDeliveryData());
    }
}
