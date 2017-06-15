<?php

namespace NYPL\Services\Model\Email;

use NYPL\Services\Model\DataModel\StreamData\HoldRequestResult;
use NYPL\Services\Model\Email;

class DeliveryEmail extends Email
{
    /**
     * @return string
     */
    public function getSubject()
    {
        return 'Electronic Document Delivery';
    }

    /**
     * @return string
     */
    public function getFromAddress()
    {
        return 'documentdelivery@nypl.org';
    }

    public function getTemplate()
    {
        return 'delivery.twig';
    }

    public function getToAddress()
    {
        /**
         * @var HoldRequestResult $holdRequestResult
         */
        $holdRequestResult = $this->getStreamData();

        return $holdRequestResult->getHoldRequest()->getDocDeliveryData()->getEmailAddress();

    }

}