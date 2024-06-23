<?php
declare(strict_types=1);

namespace App\Service\FileReader;

use App\Service\FileReader\Exception\FileReaderException;
use App\Service\FileReader\Interface\FileReaderInterface;
use App\Service\FileReader\Interface\StorageDriverInterface;

final class FileReader implements FileReaderInterface
{
    /**
     * @param \App\Service\FileReader\Interface\StorageDriverInterface[] $drivers
     */
    public function __construct(private readonly iterable $drivers)
    {
    }

    /**
     * @throws \App\Service\FileReader\Exception\FileReaderException
     */
    public function read(string $file, string $storage, int $linesPerStream): iterable
    {
        $lines = [];
        $counter = 0;

        $driver = $this->getDriver($storage);
        $stream = $driver->startStream($file);

        while ($counter < $linesPerStream) {
            if (($line = fgets($stream, 4096)) !== false) {
                $lines[] = $line;
            }
            $counter++;
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
