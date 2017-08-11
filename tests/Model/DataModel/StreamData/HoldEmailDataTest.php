<?php

namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use PHPUnit\Framework\TestCase;

class HoldEmailDataTest extends TestCase
{
    public $fakeHoldEmailData;

    public $fakeDocDeliveryData;

    public function setUp()
    {
        $this->fakeDocDeliveryData = new class extends DocDeliveryData
        {
            public function __construct($data = [
                'emailAddress' => '',
                'author' => '',
                'requestNotes' => '',
                'chapterTitle' => '',
                'issue' => '',
                'volume' => '',
                'startPage' => '',
                'endPage' => ''
            ])
            {
                parent::__construct($data);
            }
        };
        $this->fakeHoldEmailData = new class extends HoldEmailData
        {
            public function __construct($data = [
                'patronName' => '',
                'patronEmail' => '',
                'title' => '',
                'author' => '',
                'barcode' => '',
                'pickupLocation' => '',
                'deliveryLocation' => '',
                'docDeliveryData' => null,
                'requestDate' => '',
                'success' => false
            ])
            {
                parent::__construct($data);
            }
        };
        parent::setUp();
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testPatronName()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getPatronName());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testPatronEmail()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getPatronEmail());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testTitle()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getTitle());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testAuthor()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getAuthor());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testBarcode()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getBarcode());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testPickupLocation()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getPickupLocation());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testRequestDate()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getRequestDate());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testDeliveryLocation()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getDeliveryLocation());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testDocDeliveryDataCanBeNull()
    {
        $this->assertNull($this->fakeHoldEmailData->getDocDeliveryData());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testDocDeliveryData()
    {
        $this->fakeHoldEmailData->setDocDeliveryData($this->fakeDocDeliveryData);
        $this->assertEquals($this->fakeDocDeliveryData, $this->fakeHoldEmailData->getDocDeliveryData());
    }
}
