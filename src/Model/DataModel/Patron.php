<?php
namespace NYPL\Services\Model\DataModel;


use NYPL\Services\Model\DataModel;

class Patron extends DataModel
{
    protected $id = '';

    protected $barCodes = [];

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getBarCodes()
    {
        return $this->barCodes;
    }

    /**
     * @param array $barCodes
     */
    public function setBarCodes($barCodes)
    {
        $this->barCodes = $barCodes;
    }
}