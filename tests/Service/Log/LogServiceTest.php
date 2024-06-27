<?php
declare(strict_types=1);

namespace App\Tests\Service\Log;

use App\Messenger\Message\CreateLogFromFileMessage;
use App\Repository\Interface\LogRepositoryInterface;
use App\Service\FileReader\Interface\FileReaderInterface;
use App\Service\FileReader\Interface\StorageDriverInterface;
use App\Service\Log\Filter\LogFilter;
use App\Service\Log\LogService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class LogServiceTest extends KernelTestCase
{
    private FileReaderInterface $fileReaderMock;

    private LogRepositoryInterface $logRepositoryMock;

    private MessageBusInterface $messageBusMock;

    protected function setUp(): void
    {
        $this->fileReaderMock = $this->createMock(FileReaderInterface::class);
        $this->logRepositoryMock = $this->createMock(LogRepositoryInterface::class);
        $this->messageBusMock = $this->createMock(MessageBusInterface::class);
    }

    public function testFilter(): void
    {
        $criteria = [
            'serviceName' => 'Service1',
        ];
        $filter = new LogFilter($criteria);

        $this->logRepositoryMock->expects($this->once())
            ->method('countByCriteria')
            ->with($filter)
            ->willReturn(5);

        $logService = new LogService($this->fileReaderMock, $this->logRepositoryMock, $this->messageBusMock);
        $this->assertEquals(5, $logService->filter($criteria));
    }

    /**
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    public function testPopulateLogsFromFileStream(): void
    {
        $rawLogs = ["service log", "service log", "service log"];
        $settings = [
            'file' => 'test.log',
            'lines' => 10,
            'storage' => StorageDriverInterface::STORAGE_TYPE_LOCAL,
        ];

        $this->fileReaderMock->expects($this->once())
            ->method('read')
            ->with($settings['file'], $settings['storage'], $settings['lines'])
            ->willReturn($rawLogs);

        $this->messageBusMock->expects($this->exactly(count($rawLogs)))
            ->method('dispatch')
            ->with(new CreateLogFromFileMessage('service log'))
            ->willReturn(new Envelope(new \stdClass()));

        $logService = new LogService($this->fileReaderMock, $this->logRepositoryMock, $this->messageBusMock);
        $logService->populateLogsFromFileStream($settings);
    }

    /**
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    public function testPopulateLogsWithNonNumericLinesArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Arguments "lines" must be numeric.');

        $settings = [
            'file' => 'test.log',
            'lines' => 'invalid',
            'storage' => StorageDriverInterface::STORAGE_TYPE_LOCAL,
        ];

        $logService = new LogService($this->fileReaderMock, $this->logRepositoryMock, $this->messageBusMock);
        $logService->populateLogsFromFileStream($settings);
    }
}
