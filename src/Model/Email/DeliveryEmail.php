<?php

namespace NYPL\HoldRequestResultConsumer\Model\Email;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use NYPL\HoldRequestResultConsumer\Model\Email;

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
         * @var HoldEmailData $holdEmailData
         */
        $holdEmailData = $this->getStreamData();

        return $holdEmailData->getPatronEmail();
    }
}
