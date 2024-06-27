<?php
declare(strict_types=1);

namespace App\Tests\Service\Log\Exception;

use App\Service\Log\Exception\LogServiceRuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LogServiceRuntimeExceptionTest extends KernelTestCase
{
    public function testInvalidDatetimeFormat(): void
    {
        $exception = LogServiceRuntimeException::invalidDatetimeFormat();

        $this->assertEquals("Invalid datetime format.", $exception->getMessage());
    }

    public function testInvalidStatusCodeFormat(): void
    {
        $exception = LogServiceRuntimeException::invalidStatusCodeFormat();

        $this->assertEquals("Invalid HTTP status code.", $exception->getMessage());
    }
}
