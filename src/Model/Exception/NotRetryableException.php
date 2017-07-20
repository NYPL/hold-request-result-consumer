<?php

namespace NYPL\HoldRequestResultConsumer\Model\Exception;

use Exception;
use NYPL\Starter\APIException;
use NYPL\Starter\Model\Response\ErrorResponse;

class NotRetryableException extends APIException
{
    public function __construct(
        $message = '',
        $debugInfo = [],
        $code = 0,
        Exception $previous = null,
        $httpCode = 0,
        ErrorResponse $errorResponse = null
    ) {
        parent::__construct($message, $debugInfo, $code, $previous, $httpCode, $errorResponse);
    }
}
