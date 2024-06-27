<?php
declare(strict_types=1);

namespace App\Tests\Service\FileReader\Driver;

use App\Service\FileReader\Interface\StorageDriverInterface;

class LocalStorageDriverTest extends AbstractStorageDriverTest
{
    protected function setUp(): void
    {
        $this->storage = StorageDriverInterface::STORAGE_TYPE_LOCAL;

        parent::setUp();
    }

    public function testSupports(): void
    {
        $this->assertTrue($this->storageDriver->supports($this->storage));
    }
}
