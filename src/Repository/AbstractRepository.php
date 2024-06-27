<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Repository\Interface\AbstractRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository implements AbstractRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->getEntityClass());
    }

    public function createFromEntity(AbstractEntity $entity): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($entity);
        $entityManager->flush();
    }

    abstract public function getEntityClass(): string;
}
