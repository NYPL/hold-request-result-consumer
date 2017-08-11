<?php

namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData;
use PHPUnit\Framework\TestCase;

class DocDeliveryDataTest extends TestCase
{
    public $fakeDocDeliveryData;

    public function setUp()
    {
        $this->fakeDocDeliveryData = new class extends DocDeliveryData
        {
            public function __construct(
                $data = [
                    'emailAddress' => 'fake@example.com',
                    'author' => null,
                    'requestNotes' => null,
                    'chapterTitle' => '',
                    'issue' => null,
                    'volume' => null,
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
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testEmailAddress()
    {
        $this->assertEquals('fake@example.com', $this->fakeDocDeliveryData->getEmailAddress());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testAuthorCanBeNull()
    {
        $this->assertNull($this->fakeDocDeliveryData->getAuthor());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testAuthorCanBeString()
    {
        $this->fakeDocDeliveryData->setAuthor('');
        $this->assertEquals('', $this->fakeDocDeliveryData->getAuthor());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testRequestNotesCanBeNull()
    {
        $this->assertNull($this->fakeDocDeliveryData->getRequestNotes());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testRequestNotesCanBeString()
    {
        $this->fakeDocDeliveryData->setRequestNotes('');
        $this->assertEquals('', $this->fakeDocDeliveryData->getRequestNotes());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testChapterTitleCanBeString()
    {
        $this->assertEquals('', $this->fakeDocDeliveryData->getChapterTitle());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testIssueCanBeNull()
    {
        $this->assertNull($this->fakeDocDeliveryData->getIssue());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testIssueCanBeString()
    {
        $this->fakeDocDeliveryData->setIssue('');
        $this->assertEquals('', $this->fakeDocDeliveryData->getIssue());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testVolumeCanBeNull()
    {
        $this->assertNull($this->fakeDocDeliveryData->getVolume());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testVolumeCanBeString()
    {
        $this->fakeDocDeliveryData->setVolume('');
        $this->assertEquals('', $this->fakeDocDeliveryData->getVolume());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testStartPageCanBeString()
    {
        $this->assertEquals('', $this->fakeDocDeliveryData->getStartPage());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testEndPageCanBeString()
    {
        $this->assertEquals('', $this->fakeDocDeliveryData->getEndPage());
    }
}
