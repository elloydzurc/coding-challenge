<?php

namespace App\Service\FileReader\Driver;

use League\Flysystem\FilesystemOperator;

final class S3StorageDriver extends AbstractStorageDriver
{
    public function __construct(private readonly FilesystemOperator $awsStorage)
    {
        $this->filesystemOperator = $this->awsStorage;
    }

    public function supports(string $type): bool
    {
        return $type === self::STORAGE_TYPE_S3;
    }
}
