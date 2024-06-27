<?php

declare(strict_types=1);

namespace App\Tests\Service\FileReader\Exception;

use App\Service\FileReader\Exception\FileReaderException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FileReaderExceptionTest extends KernelTestCase
{
    public function testStorageNotSupported(): void
    {
        $storageType = 'azure';
        $exception = FileReaderException::storageNotSupported($storageType);

        $this->assertEquals(
            \sprintf('No available driver for storage type: %s', $storageType),
            $exception->getMessage()
        );
    }
}
