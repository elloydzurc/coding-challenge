<?php

namespace App\Service\FileReader\Interface;

interface StorageDriverInterface
{
    /**
     * @const string[]
     */
    public const array STORAGE_TYPES = [
        self::STORAGE_TYPE_LOCAL,
        self::STORAGE_TYPE_S3,
    ];

    /**
     * @const string
     */
    public const string STORAGE_TYPE_LOCAL = 'local';

    /**
     * @const string
     */
    public const string STORAGE_TYPE_S3 = 's3';

    public function endStream(): void;

    public function startStream(string $file): mixed;

    public function supports(string $type): bool;
}
