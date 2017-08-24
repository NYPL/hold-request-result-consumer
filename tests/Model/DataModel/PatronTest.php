<?php

namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use PHPUnit\Framework\TestCase;

class PatronTest extends TestCase
{
    public $fakePatron;

    public function setUp()
    {
        $this->fakePatron = new class extends Patron
        {
            public function __construct($data = [
                'id' => '',
                'barCodes' => array(),
                'names' => array(),
                'emails' => array()
            ])
            {
                parent::__construct($data);
            }
        };
        parent::setUp();
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Patron
     */
    public function testId()
    {
        $this->assertEquals('', $this->fakePatron->getId());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Patron
     */
    public function testBarCodes()
    {
        $this->assertEquals(array(), $this->fakePatron->getBarCodes());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Patron
     */
    public function testNames()
    {
        $this->assertEquals(array(), $this->fakePatron->getNames());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Patron
     */
    public function testEmails()
    {
        $this->assertEquals(array(), $this->fakePatron->getEmails());
    }
}
