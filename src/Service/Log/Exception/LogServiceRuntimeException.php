<?php

namespace App\Service\Log\Exception;

class LogServiceRuntimeException extends \RuntimeException
{
    public static function invalidDatetimeFormat(): LogServiceRuntimeException
    {
        return new self("Invalid datetime format.");
    }

    public static function invalidStatusCodeFormat(): LogServiceRuntimeException
    {
        return new self("Invalid HTTP status code.");
    }
}
