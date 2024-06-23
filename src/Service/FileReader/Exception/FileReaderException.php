<?php
declare(strict_types=1);

namespace App\Service\FileReader\Exception;

use Exception;

final class FileReaderException extends Exception
{
    public static function storageNotSupported(string $storage): self
    {
        return new self(\sprintf('No available driver for storage type: %s', $storage));
    }
}
