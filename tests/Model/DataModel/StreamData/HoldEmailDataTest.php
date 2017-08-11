<?php

namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldRequestResult;
use NYPL\HoldRequestResultConsumer\Test\Mocks\MockConfig;
use PHPUnit\Framework\TestCase;

class HoldEmailDataTest extends TestCase
{
    public $fakeHoldEmailData;

    public $fakeDocDeliveryData;

    public $fakePatron;

    public $fakeBib;

    public $fakeItem;

    public $fakeHoldRequest;

    public $fakeHoldRequestResult;

    public function setUp()
    {
        MockConfig::initialize(__DIR__ . '/../../../../');
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
                'pickupLocation' => 'mal',
                'deliveryLocation' => 'NW',
                'docDeliveryData' => null,
                'requestDate' => '',
                'success' => false
            ])
            {
                parent::__construct($data);
            }
        };

        $this->fakePatron = new class extends Patron
        {
            public function __construct($data = [
                'id' => '',
                'barCodes' => array(),
                'names' => array('Patron, Test'),
                'emails' => array('test@example.com')
            ])
            {
                parent::__construct($data);
            }
        };

        $this->fakeBib = new class extends Bib
        {
            public function __construct($data = [
                'id' => '',
                'nyplSource' => '',
                'nyplType' => '',
                'title' => '',
                'author' => ''
            ])
            {
                parent::__construct($data);
            }
        };

        $this->fakeItem = new class extends Item
        {
            public function __construct($data = [
                'id' => '',
                'nyplSource' => '',
                'bibIds' => [],
                'nyplType' => '',
                'barcode' => '',
                'callNumber' => '',
                'itemType' => ''
            ])
            {
                parent::__construct($data);
            }
        };

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
        $this->assertEquals('mal', $this->fakeHoldEmailData->getPickupLocation());
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
        $this->assertEquals('NW', $this->fakeHoldEmailData->getDeliveryLocation());
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

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testSuccess()
    {
        $this->assertFalse($this->fakeHoldEmailData->isSuccess());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData
     */
    public function testAssembleData()
    {
        $this->fakeHoldEmailData->assembleData(
            $this->fakePatron,
            $this->fakeBib,
            $this->fakeItem,
            $this->fakeHoldRequest,
            $this->fakeHoldRequestResult
        );
        $this->assertEquals('test@example.com', $this->fakeHoldEmailData->getPatronEmail());
        $this->assertEquals('Test Patron', $this->fakeHoldEmailData->getPatronName());
    }
}
