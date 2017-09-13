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

    public $fakeBibs;

    public $fakeItem;

    public $fakeHoldRequest;

    public $fakeHoldRequestResult;

    public function setUp()
    {
        MockConfig::initialize(__DIR__ . '/../../../../');

        $this->fakeHoldEmailData = new class extends HoldEmailData
        {
            public function __construct($data = [
                'patronName' => '',
                'patronEmail' => '',
                'titles' => [],
                'authors' => [],
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
                'title' => 'Test Bib Title',
                'author' => 'Test Bib Author'
            ])
            {
                parent::__construct($data);
            }
        };

        $this->fakeBibs = array($this->fakeBib, $this->fakeBib);

        $this->fakeItem = new class extends Item
        {
            public function __construct($data = [
                'id' => '',
                'nyplSource' => '',
                'bibIds' => [],
                'nyplType' => '',
                'barcode' => '32101078922455',
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
                'createdDate' => '2017-08-15T12:25:02-04:00',
                'updatedDate' => '',
                'success' => false,
                'processed' => false,
                'recordType' => '',
                'record' => '',
                'pickupLocation' => 'mal',
                'neededBy' => '',
                'numberOfCopies' => 0,
                'deliveryLocation' => 'NW',
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
    public function testAssembleData()
    {
        $this->fakeHoldEmailData->assembleData(
            $this->fakePatron,
            $this->fakeBibs,
            $this->fakeItem,
            $this->fakeHoldRequest,
            $this->fakeHoldRequestResult
        );

        $this->assertEquals($this->fakePatron->getEmails()[0], $this->fakeHoldEmailData->getPatronEmail());
        $this->assertEquals('Test Patron', $this->fakeHoldEmailData->getPatronName());
        $this->assertEquals($this->fakeBib->getTitle(), $this->fakeHoldEmailData->getTitles()[0]);
        $this->assertEquals($this->fakeBib->getAuthor(), $this->fakeHoldEmailData->getAuthors()[0]);
        $this->assertEquals('Test Delivery Location', $this->fakeHoldEmailData->getDeliveryLocation());
        $this->assertNull($this->fakeHoldEmailData->getDocDeliveryData());
        $this->assertEquals('Test Pickup Location', $this->fakeHoldEmailData->getPickupLocation());
        $this->assertEquals($this->fakeItem->getBarcode(), $this->fakeHoldEmailData->getBarcode());
        $this->assertEquals('Aug 15, 2017', $this->fakeHoldEmailData->getRequestDate());
        $this->assertFalse($this->fakeHoldEmailData->isSuccess());
    }
}
