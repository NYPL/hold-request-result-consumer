<?php
/**
 * Created by PhpStorm.
 * User: holingpoon
 * Date: 6/30/17
 * Time: 1:17 PM
 */

namespace NYPL\HoldRequestResultConsumer\Model\Email;

use NYPL\HoldRequestResultConsumer\Model\DataModel\StreamData\HoldEmailData;
use NYPL\HoldRequestResultConsumer\Model\Email;

class HoldFailureEmail extends Email
{
    /**
     * @return string
     */
    public function getSubject()
    {
        return 'NYPL Request Status Update';
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
        return 'holdfail.twig';
    }
}
