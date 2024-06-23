<?php

namespace App\Service\FileReader\Driver;

use App\Service\FileReader\Interface\StorageDriverInterface;
use League\Flysystem\FilesystemOperator;

abstract class AbstractStorageDriver implements StorageDriverInterface
{
    private const string SETTINGS_FILE = 'settings.json';

    protected FilesystemOperator $filesystemOperator;

    protected mixed $resource;

    private string $file;

    private ?array $settings = null;

    abstract public function supports(string $type): bool;

    /**
     * @throws \League\Flysystem\FilesystemException
     * @throws \JsonException
     */
    public function endStream(): void
    {
        $this->updateFilePointerFromSettings(ftell($this->resource));
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     * @throws \JsonException
     */
    public function startStream(string $file): mixed
    {
        $this->file = $file;
        $this->initializeSettings();

        $this->resource = $this->filesystemOperator->readStream($file);

        \fseek($this->resource, $this->settings[$file] ?? 0);

        return $this->resource;
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     * @throws \JsonException
     */
    private function initializeSettings(): void
    {
        if ($this->settings !== null) {
            return;
        }

        if ($this->filesystemOperator->fileExists(self::SETTINGS_FILE) === false) {
            $this->filesystemOperator->write(self::SETTINGS_FILE, '{}');
        }

        $settings = $this->filesystemOperator->read(self::SETTINGS_FILE);

        $this->settings = \json_decode(
            empty($settings) ? '{}' : $settings,
            true,
            512,
            \JSON_THROW_ON_ERROR
        );
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     * @throws \JsonException
     */
    private function updateFilePointerFromSettings(int $filePointer): void
    {
        $this->initializeSettings();
        $this->settings[$this->file] = $filePointer;

        $this->filesystemOperator->write(
            self::SETTINGS_FILE,
            \json_encode($this->settings, \JSON_THROW_ON_ERROR)
        );
    }
}
