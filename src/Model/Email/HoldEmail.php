<?php
namespace NYPL\HoldRequestResultConsumer\Model\Email;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use NYPL\HoldRequestResultConsumer\Model\Email;

class HoldEmail extends Email
{
    /**
     * @return string
     */
    public function getSubject()
    {
        return 'Your Hold Request';
    }

    /**
     * @return string
     */
    public function getFromAddress()
    {
        return 'holdrequests@nypl.org';
    }

    /**
     * @return string
     */
    public function getToAddress()
    {
        /**
         * @var HoldEmailData $holdEmailData
         */
        $holdEmailData = $this->getStreamData();

        return $holdEmailData->getPatronEmail();
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'hold.twig';
    }

}
