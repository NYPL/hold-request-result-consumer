<?php
namespace NYPL\HoldRequestResultConsumer\Test;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use PHPUnit\Framework\TestCase;

class BibTest extends TestCase
{
    public $fakeBib;
    
    public function setUp()
    {
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
        parent::setUp();
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Bib
     */
    public function testBibHasId()
    {
        $this->assertClassHasAttribute('id', Bib::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Bib
     */
    public function testId()
    {
        $this->assertEquals('', $this->fakeBib->getId());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Bib
     */
    public function testBibHasNyplSource()
    {
        $this->assertClassHasAttribute('nyplSource', Bib::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Bib
     */
    public function testNyplSource()
    {
        $this->assertEquals('', $this->fakeBib->getNyplSource());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Bib
     */
    public function testBibHasNyplType()
    {
        $this->assertClassHasAttribute('nyplType', Bib::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Bib
     */
    public function testNyplType()
    {
        $this->assertEquals('', $this->fakeBib->getNyplType());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Bib
     */
    public function testBibHasTitle()
    {
        $this->assertClassHasAttribute('title', Bib::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Bib
     */
    public function testTitle()
    {
        $this->assertEquals('', $this->fakeBib->getTitle());
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Bib
     */
    public function testBibHasAuthor()
    {
        $this->assertClassHasAttribute('author', Bib::class);
    }

    /**
     * @covers NYPL\HoldRequestResultConsumer\Model\DataModel\Bib
     */
    public function testAuthor()
    {
        $this->assertEquals('', $this->fakeBib->getAuthor());
    }
}
