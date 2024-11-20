<?php

namespace App\Exception;

use Throwable;

class SanitizeDataException extends AbstractException
{
    public const SANITIZE_ERROR_CODE = 4001;

    public function __construct($message = "", $code = self::SANITIZE_ERROR_CODE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
