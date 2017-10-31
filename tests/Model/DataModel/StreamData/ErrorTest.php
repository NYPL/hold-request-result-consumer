<?php

namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public $fakeError;

    public function setUp()
    {
        $this->fakeError = new class extends Error {
            public function __construct($data = [
              'type' => 'fake-error-type',
              'message' => "Some Fake Message"
            ])
            {
                parent::__construct($data);
            }
        };
        parent::setUp();
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\Error
     */
    public function testErrorHasType()
    {
        $this->assertClassHasAttribute('type', Error::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\Error
     */
    public function testType()
    {
        $this->assertEquals("fake-error-type", $this->fakeError->getType());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\Error
     */
    public function testErrorHasMessage()
    {
        $this->assertClassHasAttribute('message', Error::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\Error
     */
    public function testMessage()
    {
        $this->assertEquals('Some Fake Message', $this->fakeError->getMessage());
    }
}
