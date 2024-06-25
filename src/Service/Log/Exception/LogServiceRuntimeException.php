<?php

namespace App\Service\Log\Exception;

class LogServiceRuntimeException extends \RuntimeException
{
    public static function invalidDatetimeFormat(): LogServiceRuntimeException
    {
        return new self("Invalid format in filter. Should be valid datetime.");
    }

    public static function invalidStatusCodeFormat(): LogServiceRuntimeException
    {
        return new self("Invalid format in filter. Should be a valid number.");
    }
}
