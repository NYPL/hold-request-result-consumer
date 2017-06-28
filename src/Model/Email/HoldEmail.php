<?php
namespace NYPL\HoldRequestResultConsumer\Model\Email;

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
        // TODO: Return correct e-mail from Patron Info.

        return 'holingpoon@nypl.org';
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'hold.twig';
    }

}
