<?php
namespace NYPL\HoldRequestResultConsumer\Model\Email;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use NYPL\HoldRequestResultConsumer\Model\Email;

class HoldSuccessEmail extends Email
{
    /**
     * @return string
     */
    public function getSubject()
    {
        return 'NYPL Off-Site Request Status Update';
    }

    /**
     * @return string
     */
    public function getFromAddress()
    {
        return 'webfeedback@nypl.org';
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
        return 'holdsuccess.twig';
    }

}
