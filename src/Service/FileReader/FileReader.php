<?php
declare(strict_types=1);

namespace App\Service\FileReader;

use App\Service\FileReader\Exception\FileReaderException;
use App\Service\FileReader\Interface\FileReaderInterface;
use App\Service\FileReader\Interface\StorageDriverInterface;

final class FileReader implements FileReaderInterface
{
    /**
     * @const string
     */
    private const string DEFAULT_DRIVER = StorageDriverInterface::STORAGE_TYPE_LOCAL;

    /**
     * @param \App\Service\FileReader\Interface\StorageDriverInterface[] $drivers
     */
    public function __construct(private readonly iterable $drivers)
    {
    }

    /**
     * @throws \App\Service\FileReader\Exception\FileReaderException
     */
    public function read(string $file, ?string $storage = null): iterable
    {
        $lines = [];
        $driver = $this->getDriver($storage ?? self::DEFAULT_DRIVER);
        $stream = $driver->startStream($file);

        if (($line = fgets($stream, 4096)) !== false) {
            $lines[] = $line;
        }

        $driver->endStream();

        yield from $lines;
    }

    /**
     * @throws \App\Service\FileReader\Exception\FileReaderException
     */
    private function getDriver(string $storage): StorageDriverInterface
    {
        foreach ($this->drivers as $driver) {
            if ($driver->supports($storage)) {
                return $driver;
            }
        }

        throw FileReaderException::storageNotSupported($storage);
    }
}
