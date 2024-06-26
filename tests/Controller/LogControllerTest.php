<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\LogFixtures;
use App\Tests\AbstractApiTest;
use Symfony\Component\HttpFoundation\Response;

final class LogControllerTest extends AbstractApiTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(LogFixtures::class);
    }

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

    public function filterValidationDataProvider(): iterable
    {
        yield 'statusCode' => [
            'Invalid HTTP status code.',
            [
                'statusCode' => 'test',
            ],
        ];

        yield 'startDate' => [
            'Invalid datetime format.',
            [
                'startDate' => '2024-13-14',
            ],
        ];

        yield 'endDate' => [
            'Invalid datetime format.',
            [
                'endDate' => 'invalid-date',
            ],
        ];
    }

    /**
     * @throws \JsonException
     */
    public function testCountAllSuccess(): void
    {
        $response = $this->apiRequest('GET', '/count');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = \json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('count', $data);
        $this->assertEquals(5, $data['count']);
    }

    /**
     * @dataProvider filterLogDataProvider
     *
     * @throws \JsonException
     */
    public function testCountFilteredSuccess(array $filters, int $expectedCount): void
    {
        $response = $this->apiRequest(
            'GET',
            \sprintf('/count?%s', \http_build_query($filters)),
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = \json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('count', $data);
        $this->assertEquals($expectedCount, $data['count']);
    }

    /**
     * @dataProvider filterValidationDataProvider
     * @throws \JsonException
     */
    public function testValidationError(string $expectedMessage, array $queryString): void
    {
        $response = $this->apiRequest(
            'GET',
            \sprintf('/count?%s', \http_build_query($queryString)),
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $data = \json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals($expectedMessage, $data['error']);
    }
}
