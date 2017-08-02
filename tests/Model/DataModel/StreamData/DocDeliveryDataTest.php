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
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testEmailAddress()
    {
        $this->assertEquals('fake@example.com', $this->fakeDocDeliveryData->getEmailAddress());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testAuthor()
    {
        $this->assertEquals('', $this->fakeDocDeliveryData->getAuthor());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testRequestNotes()
    {
        $this->assertEquals('', $this->fakeDocDeliveryData->getRequestNotes());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testChapterTitle()
    {
        $this->assertEquals('', $this->fakeDocDeliveryData->getChapterTitle());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testIssue()
    {
        $this->assertEquals('', $this->fakeDocDeliveryData->getIssue());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testVolume()
    {
        $this->assertEquals('', $this->fakeDocDeliveryData->getVolume());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testStartPage()
    {
        $this->assertEquals('', $this->fakeDocDeliveryData->getStartPage());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\DocDeliveryData
     */
    public function testEndPage()
    {
        $this->assertEquals('', $this->fakeDocDeliveryData->getEndPage());
    }
}
