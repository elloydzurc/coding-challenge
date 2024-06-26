<?php
declare(strict_types=1);

namespace App\Tests\Command;

use App\Command\LogFileReaderCommand;
use App\Service\FileReader\Exception\FileReaderException;
use App\Service\Log\Interface\LogServiceInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class LogFileReaderCommandTest extends KernelTestCase
{
    public function argumentsValidateDataProvider(): iterable
    {
        yield 'Unsupported Storage' => [
            [
                'file' => 'logs.log',
                'storage' => 'azure',
                'lines' => 10,
            ],
            FileReaderException::class,
            'No available driver for storage type: azure',
        ];

        yield 'Lines must be numeric' => [
            [
                'file' => 'logs.log',
                'storage' => 'local',
                'lines' => 'test',
            ],
            InvalidArgumentException::class,
            'Arguments "lines" must be numeric.',
        ];
    }

    public function testLogFileReaderCommandSuccess(): void
    {
        $arguments = [
            'file' => 'logs.log',
            'storage' => 'local',
            'lines' => 10,
        ];

        $logService = $this->createMock(LogServiceInterface::class);
        $logService->method('populateLogsFromFileStream')
            ->with($arguments);

        $kernel = self::bootKernel();
        $kernel->getContainer()->set(LogServiceInterface::class, $logService);

        $command = new LogFileReaderCommand($logService);
        $tester = new CommandTester($command);

        $tester->execute($arguments);
        $this->assertEmpty($tester->getDisplay());
        $this->assertSame(Command::SUCCESS, $tester->getStatusCode());
    }


    /**
     * @dataProvider argumentsValidateDataProvider
     */
    public function testLogFileReaderCommandThrowException(
        array $arguments,
        string $exception,
        string $exceptionMessage
    ): void {
        $logService = $this->createMock(LogServiceInterface::class);
        $logService->method('populateLogsFromFileStream')
            ->with($arguments)
            ->willThrowException(new $exception($exceptionMessage));

        $kernel = self::bootKernel();
        $kernel->getContainer()->set(LogServiceInterface::class, $logService);

        $command = new LogFileReaderCommand($logService);
        $tester = new CommandTester($command);

        $tester->execute($arguments);
        $this->assertSame(Command::FAILURE, $tester->getStatusCode());
        $this->assertStringContainsString($exceptionMessage, $tester->getDisplay());
    }
}
