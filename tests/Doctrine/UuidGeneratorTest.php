<?php
declare(strict_types = 1);

namespace App\Tests\Doctrine;

use App\Doctrine\UuidGenerator;
use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UuidGeneratorTest extends KernelTestCase
{
    /**
     * @throws \Exception
     */
    public function testGenerateIdForNonAbstractEntity(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $uuidGenerator = new UuidGenerator();

        $generatedId = $uuidGenerator->generateId($entityManagerMock, null);

        $this->assertTrue(Uuid::isValid($generatedId));
    }

    /**
     * @throws \Exception
     */
    public function testGenerateIdForEntityWithoutId(): void
    {
        $log = new Log();

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $uuidGenerator = new UuidGenerator();

        $generatedId = $uuidGenerator->generateId($entityManagerMock, $log);

        $this->assertTrue(Uuid::isValid($generatedId));
        $this->assertNull($log->getId());
    }

    /**
     * @throws \Exception
     */
    public function testGenerateIdForEntityWithId(): void
    {
        $log = new Log();
        $log->setId('log-id');

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $uuidGenerator = new UuidGenerator();

        $generatedId = $uuidGenerator->generateId($entityManagerMock, $log);

        $this->assertEquals('log-id', $generatedId);
    }
}
