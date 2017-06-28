<?php
namespace NYPL\HoldRequestResultConsumer\Model\DataModel;

use NYPL\HoldRequestResultConsumer\Model\DataModel;

class Patron extends DataModel
{
    /**
     * Patron Id
     *
     * @var string
     */
    protected $id = '';

    /**
     * Patron's barcodes
     *
     * @var array
     */
    protected $barCodes = [];

    /**
     * Patron's names
     *
     * @var array
     */
    protected $names = [];

    /**
     * Patron's e-mails
     *
     * @var array
     */
    protected $emails = [];

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

    /**
     * @return array
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * @param array $names
     */
    public function setNames(array $names)
    {
        $this->names = $names;
    }

    /**
     * @return array
     */
    public function getEmails(): array
    {
        return $this->emails;
    }

    /**
     * @param array $emails
     */
    public function setEmails(array $emails)
    {
        $this->emails = $emails;
    }
}
