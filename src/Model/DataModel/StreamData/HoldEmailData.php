<?php
namespace NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Location;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;

class HoldEmailData extends StreamData
{
    /**
     * @var string
     */
    public $patronName = '';

    /**
     * @var string
     */
    public $patronEmail = '';

    /**
     * @var string
     */
    public $title = '';

    /**
     * @var string
     */
    public $author = '';

    /**
     * @var string
     */
    public $barcode = '';

    /**
     * @var string
     */
    public $pickupLocation = '';

    /**
     * @var string
     */
    public $deliveryLocation = '';


    /**
     * @var DocDeliveryData | null
     */
    public $docDeliveryData;

    /**
     * @var bool
     */
    public $success;

    /**
     * @param Patron $patron
     * @param Bib $bib
     * @param Item $item
     * @param HoldRequest $holdRequest
     * @param HoldRequestResult $holdRequestResult
     */
    public function assembleData(
        Patron $patron,
        Bib $bib,
        Item $item,
        HoldRequest $holdRequest,
        HoldRequestResult $holdRequestResult
    ) {
        $this->setAuthor($bib->getAuthor());
        $this->setBarcode($item->getBarcode());
        $this->setTitle($bib->getTitle());
        $this->setDeliveryLocation($holdRequest->getDeliveryLocation());
        $this->setPickupLocation($this->fixPickupLocation($holdRequest->getPickupLocation()));
        $this->setSuccess($holdRequestResult->isSuccess());

        $this->setPatronName($this->fixPatronName($patron));

        $this->setPatronEmail($this->fixPatronEmail($holdRequest, $patron));

        $this->setDocDeliveryData($holdRequest->getDocDeliveryData());
    }

    /**
     * @param Patron $patron
     * @return string
     */
    public function fixPatronName(Patron $patron): string
    {
        $name = $patron->getNames()[0];
        $fullName = explode(",", $name);
        $fullName[1] = trim($fullName[1]);
        $name = ucfirst(strtolower($fullName[1])) . " " . ucfirst(strtolower($fullName[0]));
        return $name;
    }

    /**
     * @param HoldRequest $holdRequest
     * @param Patron $patron
     * @return string
     */
    public function fixPatronEmail(HoldRequest $holdRequest, Patron $patron): string
    {
        /**
         * @var DocDeliveryData | null
         */
        $docDeliveryData = $holdRequest->getDocDeliveryData();
        $email = '';

        if ($docDeliveryData !== null) {
            $email = $docDeliveryData->getEmailAddress();
        }

        // If request is not an EDD, use e-mail from patron's info.
        if ($email !== '') {
            return $email;
        } elseif (count($patron->getEmails()) > 0) {
            return $patron->getEmails()[0];
        }
        return '';
    }

    /**
     * @param $pickupLocation
     * @return string
     */
    public function fixPickupLocation($pickupLocation)
    {
        return (Location::getLocationName($pickupLocation));
    }

    /**
     * @return string
     */
    public function getPatronName(): string
    {
        return $this->patronName;
    }

    /**
     * @param string $patronName
     */
    public function setPatronName(string $patronName)
    {
        $this->patronName = $patronName;
    }

    /**
     * @return string
     */
    public function getPatronEmail(): string
    {
        return $this->patronEmail;
    }

    /**
     * @param string $patronEmail
     */
    public function setPatronEmail(string $patronEmail)
    {
        $this->patronEmail = $patronEmail;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getBarcode(): string
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     */
    public function setBarcode(string $barcode)
    {
        $this->barcode = $barcode;
    }

    /**
     * @return string
     */
    public function getPickupLocation(): string
    {
        return $this->pickupLocation;
    }

    /**
     * @param string $pickupLocation
     */
    public function setPickupLocation(string $pickupLocation)
    {
        $this->pickupLocation = $pickupLocation;
    }

    /**
     * @return string
     */
    public function getDeliveryLocation(): string
    {
        return $this->deliveryLocation;
    }

    /**
     * @param string $deliveryLocation
     */
    public function setDeliveryLocation(string $deliveryLocation)
    {
        $this->deliveryLocation = $deliveryLocation;
    }

    /**
     * @return DocDeliveryData | null
     */
    public function getDocDeliveryData()
    {
        return $this->docDeliveryData;
    }

    /**
     * @param DocDeliveryData | null $docDeliveryData
     */
    public function setDocDeliveryData($docDeliveryData)
    {
        $this->docDeliveryData = $docDeliveryData;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success)
    {
        $this->success = $success;
    }
}
