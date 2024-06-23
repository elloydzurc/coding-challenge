<?php
declare(strict_types=1);

namespace App\Doctrine;

use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Ramsey\Uuid\Uuid;

final class UuidGenerator extends AbstractIdGenerator
{
    /**
     * @throws \Exception
     */
    public function generateId(EntityManagerInterface $em, ?object $entity): string
    {
        if ($entity instanceof AbstractEntity === false) {
            return Uuid::uuid4()->toString();
        }

        return $entity->getId() ?? Uuid::uuid4()->toString();
    }
}
