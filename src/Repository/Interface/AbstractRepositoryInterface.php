<?php
declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\AbstractEntity;

interface AbstractRepositoryInterface
{
    public function createFromEntity(AbstractEntity $entity): void;
}
