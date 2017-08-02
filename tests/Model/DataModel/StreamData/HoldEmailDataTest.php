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

    public function testPatronName()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getPatronName());
    }

    public function testPatronEmail()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getPatronEmail());
    }

    public function testTitle()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getTitle());
    }

    public function testAuthor()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getAuthor());
    }

    public function testBarcode()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getBarcode());
    }

    public function testPickupLocation()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getPickupLocation());
    }

    public function testRequestDate()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getRequestDate());
    }

    public function testDeliveryLocation()
    {
        $this->assertEquals('', $this->fakeHoldEmailData->getDeliveryLocation());
    }

    public function testDocDeliveryDataCanBeNull()
    {
        $this->assertNull($this->fakeHoldEmailData->getDocDeliveryData());
    }

    public function testDocDeliveryData()
    {
        $this->fakeHoldEmailData->setDocDeliveryData($this->fakeDocDeliveryData);
        $this->assertEquals($this->fakeDocDeliveryData, $this->fakeHoldEmailData->getDocDeliveryData());
    }
}
