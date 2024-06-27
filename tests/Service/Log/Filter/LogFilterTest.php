<?php
declare(strict_types=1);

namespace App\Tests\Service\Log\Filter;

use App\Service\Log\Exception\LogServiceRuntimeException;
use App\Service\Log\Filter\LogFilter;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogFilterTest extends WebTestCase
{
    public function testBuildMethod(): void
    {
        $criteria = [
            'endDate' => '2023-01-01 12:00:00',
            'serviceName' => 'USER-SERVICE,INVOICE-SERVICE',
            'startDate' => '2023-01-01 12:00:00',
            'statusCode' => 200,
        ];

        $filter = new LogFilter($criteria);

        $this->assertInstanceOf(Carbon::class, $filter->getEndDate());
        $this->assertInstanceOf(Carbon::class, $filter->getStartDate());

        $this->assertIsArray($filter->getServiceName());
        $this->assertCount(2, $filter->getServiceName());
        $this->assertEquals([
            'USER-SERVICE',
            'INVOICE-SERVICE',
        ], $filter->getServiceName());

        $this->assertEquals(200, $filter->getStatusCode());
    }

    public function testGetEndDate(): void
    {
        $criteria = [
            'endDate' => '2023-01-01 12:00:00',
        ];

        $filter = new LogFilter($criteria);

        $this->assertInstanceOf(Carbon::class, $filter->getEndDate());
        $this->assertEquals('2023-01-01 12:00:00', $filter->getEndDate()->format('Y-m-d H:i:s'));
    }

    public function testGetEndDateWithNull(): void
    {
        $filter = new LogFilter();

        $this->assertNull($filter->getEndDate());
    }

    public function testGetServiceName(): void
    {
        $criteria = [
            'serviceName' => 'USER-SERVICE,INVOICE-SERVICE',
        ];

        $filter = new LogFilter($criteria);

        $this->assertIsArray($filter->getServiceName());
        $this->assertCount(2, $filter->getServiceName());
        $this->assertEquals([
            'USER-SERVICE',
            'INVOICE-SERVICE',
        ], $filter->getServiceName());
    }

    public function testGetServiceNameWithArray(): void
    {
        $criteria = [
            'serviceName' => [
                'USER-SERVICE',
                'INVOICE-SERVICE',
            ],
        ];

        $filter = new LogFilter($criteria);

        $this->assertIsArray($filter->getServiceName());
        $this->assertCount(2, $filter->getServiceName());
        $this->assertEquals([
            'USER-SERVICE',
            'INVOICE-SERVICE',
        ], $filter->getServiceName());
    }

    public function testGetServiceNameWithNull(): void
    {
        $filter = new LogFilter();

        $this->assertNull($filter->getServiceName());
    }

    public function testGetStartDate(): void
    {
        $criteria = [
            'startDate' => '2023-01-01 12:00:00',
        ];

        $filter = new LogFilter($criteria);

        $this->assertInstanceOf(Carbon::class, $filter->getStartDate());
        $this->assertEquals('2023-01-01 12:00:00', $filter->getStartDate()->format('Y-m-d H:i:s'));
    }

    public function testGetStartDateWithNull(): void
    {
        $filter = new LogFilter();

        $this->assertNull($filter->getStartDate());
    }

    public function testGetStatusCode(): void
    {
        $criteria = ['statusCode' => 200];
        $filter = new LogFilter($criteria);

        $this->assertEquals(200, $filter->getStatusCode());
    }

    public function testGetStatusCodeWithInvalidFormat(): void
    {
        $this->expectException(LogServiceRuntimeException::class);
        $this->expectExceptionMessage('Invalid HTTP status code.');

        $criteria = ['statusCode' => 'invalid'];
        $filter = new LogFilter($criteria);

        $filter->getStatusCode();
    }

    public function testParseDateTimeWithInvalidFormat(): void
    {
        $this->expectException(LogServiceRuntimeException::class);
        $this->expectExceptionMessage('Invalid datetime format.');

        $criteria = ['startDate' => 'invalid-date'];
        $filter = new LogFilter($criteria);

        $filter->getStartDate();
    }
}
