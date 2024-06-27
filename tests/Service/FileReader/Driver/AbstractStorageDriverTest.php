<?php
declare(strict_types=1);

namespace App\Tests\Service\FileReader\Driver;

use App\Service\FileReader\Driver\AbstractStorageDriver;
use App\Service\FileReader\Driver\LocalStorageDriver;
use App\Service\FileReader\Driver\S3StorageDriver;
use App\Service\FileReader\Interface\StorageDriverInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AbstractStorageDriverTest extends KernelTestCase
{
    protected ?string $storage = null;

    protected StorageDriverInterface $storageDriver;

    protected FilesystemOperator $filesystemOperator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystemOperator = $this->createMock(FilesystemOperator::class);

        $this->storageDriver = $this->storage === StorageDriverInterface::STORAGE_TYPE_S3 ?
            new S3StorageDriver($this->filesystemOperator) : new LocalStorageDriver($this->filesystemOperator);
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     * @throws \JsonException
     */
    public function testStartStream(): void
    {
        $file = 'test.log';

        $this->filesystemOperator->expects($this->once())
            ->method('readStream')
            ->with($file)
            ->willReturn(fopen('data://text/plain,some content', 'rb'));

        $this->filesystemOperator->expects($this->once())
            ->method('fileExists')
            ->with(AbstractStorageDriver::SETTINGS_FILE)
            ->willReturn(false);

        $this->filesystemOperator->expects($this->once())
            ->method('write')
            ->with(AbstractStorageDriver::SETTINGS_FILE, '{}');

        $this->assertIsResource($this->storageDriver->startStream($file));
    }
}
