<?php

namespace App\Service\FileReader\Driver;

use League\Flysystem\FilesystemOperator;

final class LocalStorageDriver extends AbstractStorageDriver
{
    public function __construct(private readonly FilesystemOperator $defaultStorage)
    {
        $this->filesystemOperator = $this->defaultStorage;
    }

    public function supports(string $type): bool
    {
        return $type === self::STORAGE_TYPE_LOCAL;
    }
}
