<?php

namespace App\Repository\Interface;

use App\Service\Log\Filter\LogFilter;

interface LogRepositoryInterface extends AbstractRepositoryInterface
{
    public function countByCriteria(LogFilter $filter): int;
}
