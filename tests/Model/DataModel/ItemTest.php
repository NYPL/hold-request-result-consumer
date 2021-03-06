<?php

namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public $fakeItem;

    public function setUp()
    {
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
        parent::setUp();
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testItemHasId()
    {
        $this->assertClassHasAttribute('id', Item::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testIdCanBeBlank()
    {
        $this->assertEquals('', $this->fakeItem->getId());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testIdCanBeSetAsString()
    {
        $this->fakeItem->setId('0');
        $this->assertEquals('0', $this->fakeItem->getId());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testItemHasNyplSource()
    {
        $this->assertClassHasAttribute('nyplSource', Item::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testNyplSourceCanBeBlank()
    {
        $this->assertEquals('', $this->fakeItem->getNyplSource());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testNyplSourceCanBeSetAsString()
    {
        $this->fakeItem->setNyplSource('sierra-nypl');
        $this->assertEquals('sierra-nypl', $this->fakeItem->getNyplSource());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testItemHasBibIds()
    {
        $this->assertClassHasAttribute('bibIds', Item::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testBibIdsCanBeEmpty()
    {
        $this->assertEquals(array(), $this->fakeItem->getBibIds());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testBibIdsCanBeSetAsArray()
    {
        $this->fakeItem->setBibIds(array('1234567'));
        $this->assertContains('1234567', $this->fakeItem->getBibIds());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testItemHasNyplType()
    {
        $this->assertClassHasAttribute('nyplType', Item::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testNyplTypeCanBeBlank()
    {
        $this->assertEquals('', $this->fakeItem->getNyplType());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testNyplTypeCanBeSetAsString()
    {
        $this->fakeItem->setNyplType('i');
        $this->assertEquals('i', $this->fakeItem->getNyplType());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testItemHasBarcode()
    {
        $this->assertClassHasAttribute('barcode', Item::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testBarcodeCanBeBlank()
    {
        $this->assertEquals('', $this->fakeItem->getBarCode());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testBarcodeCanBeSetAsString()
    {
        $this->fakeItem->setBarCode('111122233334444');
        $this->assertEquals('111122233334444', $this->fakeItem->getBarCode());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testItemHasCallNumber()
    {
        $this->assertClassHasAttribute('callNumber', Item::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testCallNumberCanBeBlank()
    {
        $this->assertEquals('', $this->fakeItem->getCallNumber());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testCallNumberCanBeSetAsString()
    {
        $this->fakeItem->setCallNumber('J12345678');
        $this->assertEquals('J12345678', $this->fakeItem->getCallNumber());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testItemHasItemType()
    {
        $this->assertClassHasAttribute('itemType', Item::class);
    }
    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testItemTypeCanBeBlank()
    {
        $this->assertEquals('', $this->fakeItem->getItemType());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testItemTypeCanBeSetAsString()
    {
        $this->fakeItem->setItemType('b');
        $this->assertEquals('b', $this->fakeItem->getItemType());
    }
}
