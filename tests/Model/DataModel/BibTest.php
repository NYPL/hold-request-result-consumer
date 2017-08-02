<?php

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

    public function testId()
    {
        $this->assertEquals('', $this->fakeBib->getId());
    }

    public function testNyplSource()
    {
        $this->assertEquals('', $this->fakeBib->getNyplSource());
    }

    public function testNyplType()
    {
        $this->assertEquals('', $this->fakeBib->getNyplType());
    }

    public function testTitle()
    {
        $this->assertEquals('', $this->fakeBib->getTitle());
    }

    public function testAuthor()
    {
        $this->assertEquals('', $this->fakeBib->getAuthor());
    }
}
