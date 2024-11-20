<?php

namespace App\Exception;

use Throwable;

class MissingRequiredFieldsException extends AbstractException
{
    public const REQUIRED_VALUE_CODE = 4002;

    public function __construct($message = "", $code = self::REQUIRED_VALUE_CODE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
