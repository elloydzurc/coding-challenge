<?php
declare(strict_types=1);

namespace App\Service\Log;

use App\Repository\Interface\LogRepositoryInterface;
use App\Service\Log\Filter\LogFilter;
use App\Service\Log\Interface\LogAnalyticsInterface;

final class LogAnalytics implements LogAnalyticsInterface
{
    public function __construct(private readonly LogRepositoryInterface $logRepository)
    {
    }

    public function filter(array $criteria): int
    {
        return $this->logRepository->countByCriteria(new LogFilter($criteria));
    }
}
