<?php
declare(strict_types=1);

namespace App\Tests\Service\FileReader\Driver;

use App\Service\FileReader\Interface\StorageDriverInterface;

class S3StorageDriverTest extends AbstractStorageDriverTest
{
    protected function setUp(): void
    {
        $this->storage = StorageDriverInterface::STORAGE_TYPE_S3;

        parent::setUp();
    }

    public function testSupports(): void
    {
        $this->assertTrue($this->storageDriver->supports($this->storage));
    }
}
