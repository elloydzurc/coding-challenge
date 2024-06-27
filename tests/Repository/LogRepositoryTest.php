<?php
declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\LogFixtures;
use App\Entity\Log;
use App\Repository\Interface\LogRepositoryInterface;
use App\Service\Log\Filter\LogFilter;
use App\Tests\AbstractFixtureTest;
use Symfony\Component\HttpFoundation\Response;

class LogRepositoryTest extends AbstractFixtureTest
{
    private LogRepositoryInterface $logRepository;

    public function filterLogDataProvider(): iterable
    {
        yield 'Filter By Service Name' => [
            ['serviceName' => LogFixtures::INVOICE_SERVICE],
            2
        ];

        yield 'Filter By Status Code' => [
            ['statusCode' => Response::HTTP_CREATED],
            2
        ];

        yield 'Filter By Start Date' => [
            ['startDate' => '2024-01-04 01:33:21'],
            1
        ];

        yield 'Filter By Start Date & End Date' => [
            ['startDate' => '2024-01-03 02:13:59', 'endDate' => '2024-01-04 14:14:14'],
            2
        ];

        yield 'Filter By Service Name and Status Code' => [
            ['serviceName' => LogFixtures::USER_SERVICE, 'statusCode' => Response::HTTP_BAD_REQUEST],
            2
        ];

        yield 'Filter By Service Name, Start Date and End Date' => [
            [
                'serviceName' => LogFixtures::INVOICE_SERVICE,
                'startDate' => '2024-01-03 01:23:17',
                'endDate' => '2024-01-04 08:37:31'
            ],
            1
        ];

        yield 'Filter By Status Code, Start Date and End Date' => [
            [
                'statusCode' => Response::HTTP_CREATED,
                'startDate' => '2024-01-05 23:43:11',
                'endDate' => '2024-01-06 19:06:51'
            ],
            0
        ];
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->logRepository = $this->entityManager->getRepository(Log::class);

        $this->loadFixtures(LogFixtures::class);
    }

    /**
     * @dataProvider filterLogDataProvider
     */
    public function testCountByCriteria(array $criteria, int $expectedCount): void
    {
        $filter = new LogFilter($criteria);
        $this->assertEquals($expectedCount, $this->logRepository->countByCriteria($filter));
    }

    public function testFindByHash(): void
    {
        $log = $this->logRepository->findByHash(\md5('log3'));
        $this->assertInstanceOf(Log::class, $log);

        $log = $this->logRepository->findByHash(\md5('log10'));
        $this->assertNull($log);
    }

    public function testGetEntityClass(): void
    {
        self::assertEquals(Log::class, $this->logRepository->getEntityClass());
    }
}
