<?php
namespace NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use NYPL\HoldRequestResultConsumer\Model\DataModel\HoldRequest;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Item;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Location;
use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData;
use NYPL\HoldRequestResultConsumer\Model\Exception\NonRetryableException;
use NYPL\HoldRequestResultConsumer\OAuthClient\LocationClient;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Model\LocalDateTime;
use NYPL\Starter\Model\Response\ErrorResponse;

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
     * @var array
     */
    public $titles = array();

    /**
     * @var array
     */
    public $authors = array();

    /**
     * @var string
     */
    public $barcode = '';

    /**
     * @var string|null
     */
    public $pickupLocation;

    /**
     * @var string|null
     */
    public $deliveryLocation;


    /**
     * @var DocDeliveryData | null
     */
    public $docDeliveryData;

    /**
     * @var string
     */
    public $requestDate = '';

    /**
     * @var bool
     */
    public $success;

    /**
     * @param Patron $patron
     * @param array $bibs
     * @param Item $item
     * @param HoldRequest $holdRequest
     * @param HoldRequestResult $holdRequestResult
     */
    public function assembleData(
        Patron $patron,
        array $bibs,
        Item $item,
        HoldRequest $holdRequest,
        HoldRequestResult $holdRequestResult
    ) {
        $this->setAuthors(array_column($bibs, 'author'));
        $this->setBarcode($item->getBarcode());
        $this->setTitles(array_column($bibs, 'title'));
        $this->setDeliveryLocation($this->fixDeliveryLocation($holdRequest->getDeliveryLocation()));
        $this->setPickupLocation($this->fixPickupLocation($holdRequest->getPickupLocation()));
        $this->setSuccess($holdRequestResult->isSuccess());

        $this->setPatronName($this->fixPatronName($patron));

        $this->setPatronEmail($this->fixPatronEmail($holdRequest, $patron));

        $this->setDocDeliveryData($holdRequest->getDocDeliveryData());

        if ($holdRequest->getCreatedDate() !== null) {
            $creationDate = new LocalDateTime(LocalDateTime::FORMAT_DATE, $holdRequest->getCreatedDate());
            $this->setRequestDate($creationDate->getDateTime()->format('M j, Y'));
        }
    }


    /**
     * @param Patron $patron
     * @return string
     * @throws NonRetryableException
     */
    public function fixPatronName(Patron $patron): string
    {

        if (count($patron->getNames()) > 0) {
            $name = $patron->getNames()[0];
            $fullName = explode(",", $name);
            if (count($fullName) > 1) {
                $fullName[1] = trim($fullName[1]);
                $name = strtolower($fullName[1]) . " " . strtolower($fullName[0]);
                $name = ucwords($name, "(- \t\r\n\f\v");
            }
            return $name;
        } else {
            throw new NonRetryableException(
                'Not Acceptable: No names for patron',
                [],
                406,
                null,
                406,
                new ErrorResponse(406, 'no-patron-name', 'Not Acceptable: No names for patron')
            );
        }
    }

    /**
     * @param HoldRequest $holdRequest
     * @param Patron $patron
     * @return string
     * @throws NonRetryableException
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
        } else {
            throw new NonRetryableException(
                'No-email',
                'Patron did not provide an e-mail address.',
                0,
                null,
                406,
                new ErrorResponse(406, 'no-email', 'Patron did not provide an e-mail address')
            );
        }
    }

    /**
     * @param $pickupLocation
     * @return string
     */
    public function fixPickupLocation($pickupLocation)
    {
        return (LocationClient::getSierraLocationById($pickupLocation));
    }

    /**
     * @param $deliveryLocation
     * @return string
     */
    public function fixDeliveryLocation($deliveryLocation)
    {
        return (LocationClient::getRecapLocationById($deliveryLocation));
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
     * @return array
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @param array $titles
     */
    public function setTitles(array $titles)
    {
        $this->titles = $titles;
    }

    /**
     * @return array
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param array $authors
     */
    public function setAuthors(array $authors)
    {
        $this->authors = $authors;
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
     * @return string|null
     */
    public function getPickupLocation()
    {
        return $this->pickupLocation;
    }

    /**
     * @param string|null $pickupLocation
     */
    public function setPickupLocation($pickupLocation)
    {
        $this->pickupLocation = $pickupLocation;
    }

    /**
     * @return string
     */
    public function getRequestDate(): string
    {
        return $this->requestDate;
    }/**
     * @param string $requestDate
     */
    public function setRequestDate(string $requestDate)
    {
        $this->requestDate = $requestDate;
    }

    /**
     * @return string|null
     */
    public function getDeliveryLocation()
    {
        return $this->deliveryLocation;
    }

    /**
     * @param string|null $deliveryLocation
     */
    public function setDeliveryLocation($deliveryLocation)
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
