<?php
declare(strict_types=1);

namespace App\Service\Log\Interface;

interface LogAnalyticsInterface
{
    public function filter(array $criteria): int;
}
