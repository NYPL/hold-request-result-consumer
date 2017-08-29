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
    public function testAttributesExist()
    {
        $this->assertClassHasAttribute('id', Item::class);
        $this->assertClassHasAttribute('nyplSource', Item::class);
        $this->assertClassHasAttribute('bibIds', Item::class);
        $this->assertClassHasAttribute('nyplType', Item::class);
        $this->assertClassHasAttribute('barcode', Item::class);
        $this->assertClassHasAttribute('callNumber', Item::class);
        $this->assertClassHasAttribute('itemType', Item::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testId()
    {
        $this->assertEquals('', $this->fakeItem->getId());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testNyplSource()
    {
        $this->assertEquals('', $this->fakeItem->getNyplSource());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testBibIds()
    {
        $this->assertEquals(array(), $this->fakeItem->getBibIds());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testNyplType()
    {
        $this->assertEquals('', $this->fakeItem->getNyplType());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testBarcode()
    {
        $this->assertEquals('', $this->fakeItem->getBarCode());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testCallNumber()
    {
        $this->assertEquals('', $this->fakeItem->getCallNumber());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Item
     */
    public function testItemType()
    {
        $this->assertEquals('', $this->fakeItem->getItemType());
    }
}
