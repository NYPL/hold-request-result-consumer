<?php
namespace NYPL\HoldRequestResultConsumer\Model\Email;

use NYPL\HoldRequestResultConsumer\Model\Email;

class HoldEmail extends Email
{
    public function getSubject()
    {
        return 'Your Hold Request';
    }

    public function getFromAddress()
    {
        return 'holdrequests@nypl.org';
    }

    public function getToAddress()
    {
        // TODO: Return correct e-mail from Patron Info.

        return 'holingpoon@nypl.org';
    }

    public function getTemplate()
    {
        return 'hold.twig';
    }

}
