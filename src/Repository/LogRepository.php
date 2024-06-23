<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Log;
use App\Repository\Interface\LogRepositoryInterface;
use App\Service\Log\Filter\LogFilter;

final class LogRepository extends AbstractRepository implements LogRepositoryInterface
{
    public function countByCriteria(LogFilter $filter): int
    {
        $builder = $this->createQueryBuilder('l');

        $builder->select('COUNT(l.id)');

        if ($filter->getServiceName()) {
            $builder->andWhere($builder->expr()->eq('l.serviceName', ':serviceName'))
                ->setParameter('serviceName', $filter->getServiceName());
        }

        if ($filter->getStatusCode()) {
            $builder->andWhere($builder->expr()->eq('l.statusCode', ':statusCode'))
                ->setParameter('statusCode', $filter->getStatusCode());
        }

        if ($filter->getStartDate()) {
            $builder->andWhere($builder->expr()->gte('l.requestDate', ':startDate'))
                ->setParameter('startDate', $filter->getStartDate());
        }

        if ($filter->getEndDate()) {
            $builder->andWhere($builder->expr()->lte('l.requestDate', ':endDate'))
                ->setParameter('endDate', $filter->getEndDate());
        }

        $count = $builder->getQuery()->getSingleScalarResult();

        return $count === null ? 0 : (int)$count;
    }

    protected function getEntityClass(): string
    {
        return Log::class;
    }
}
