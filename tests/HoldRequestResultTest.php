<?php

use NYPL\Services\Model\DataModel\StreamData\HoldRequestResult;
use PHPUnit\Framework\TestCase;

class HoldRequestResultTest extends TestCase
{
    public $fakeHoldRequestResult;

    public function setUp() {
        $this->fakeHoldRequestResult = new class extends HoldRequestResult {
            public function __construct($data = ['message' => 'fakeHoldRequestResult'])
            {
                parent::__construct($data);
            }
        };
        parent::setUp();
    }

    /**
     * @covers NYPL\Services\Model\DataModel\StreamData\HoldRequestResult
     */
    public function testMessage()
    {
        $this->assertEquals('fakeHoldRequestResult', $this->fakeHoldRequestResult->message);
    }
}