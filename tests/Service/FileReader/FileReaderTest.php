<?php

declare(strict_types=1);

namespace App\Tests\Service\FileReader;

use App\Service\FileReader\Exception\FileReaderException;
use App\Service\FileReader\FileReader;
use App\Service\FileReader\Interface\FileReaderInterface;
use App\Service\FileReader\Interface\StorageDriverInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileReaderTest extends WebTestCase
{
    private array $drivers;

    private FileReaderInterface $fileReader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->drivers = [
            $this->createMock(StorageDriverInterface::class),
            $this->createMock(StorageDriverInterface::class),
        ];

        $this->fileReader = new FileReader($this->drivers);
    }

    /**
     * @throws \App\Service\FileReader\Exception\FileReaderException
     */
    public function testRead(): void
    {
        $file = 'test.log';
        $storage = 'local';
        $linesPerStream = 2;
        $expectedLines = ["line 1\n", "line 2\n"];

        $driverMock = $this->drivers[0];
        $driverMock->expects($this->once())
            ->method('supports')
            ->with($storage)
            ->willReturn(true);

        $driverMock->expects($this->once())
            ->method('startStream')
            ->with($file)
            ->willReturn(\fopen('data://text/plain,' . \implode('', $expectedLines), 'rb'));

        $driverMock->expects($this->once())
            ->method('endStream');

        $result = \iterator_to_array($this->fileReader->read($file, $storage, $linesPerStream));

        $this->assertEquals($expectedLines, $result);
    }

    public function testReadWithUnsupportedStorage(): void
    {
        $file = 'test.log';
        $storage = 'azure';
        $linesPerStream = 2;

        $this->expectException(FileReaderException::class);
        $this->expectExceptionMessage(\sprintf('No available driver for storage type: %s', $storage));

        $driverMock = $this->drivers[0];
        $driverMock->expects($this->once())
            ->method('supports')
            ->with($storage)
            ->willReturn(false);

        $output = $this->fileReader->read($file, $storage, $linesPerStream);
        $output->valid();
    }
}
