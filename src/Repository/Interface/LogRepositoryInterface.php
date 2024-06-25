<?php

namespace App\Repository\Interface;

use App\Entity\Log;
use App\Service\Log\Filter\LogFilter;

interface LogRepositoryInterface extends AbstractRepositoryInterface
{
    public function countByCriteria(LogFilter $filter): int;

    public function findByHash(string $hash): ?Log;
}
