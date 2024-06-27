<?php

namespace App\DataFixtures;

use App\Entity\Log;
use Carbon\Carbon;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

class LogFixtures extends Fixture
{
    public const string INVOICE_SERVICE = 'INVOICE-SERVICE';

    public const string USER_SERVICE = 'USER-SERVICE';

    public function load(ObjectManager $manager): void
    {
        $log1 = $this->createLog(
            Carbon::parse('2024-01-01 00:00:00'),
            'log1',
            self::USER_SERVICE,
            Response::HTTP_CREATED
        );
        $manager->persist($log1);

        $log2 = $this->createLog(
            Carbon::parse('2024-01-01 11:22:33'),
            'log2',
            self::USER_SERVICE,
            Response::HTTP_BAD_REQUEST
        );
        $manager->persist($log2);

        $log3 = $this->createLog(
            Carbon::parse('2024-01-03 07:11:42'),
            'log3',
            self::INVOICE_SERVICE,
            Response::HTTP_BAD_REQUEST
        );
        $manager->persist($log3);

        $log4 = $this->createLog(
            Carbon::parse('2024-01-04 10:11:12'),
            'log4',
            self::INVOICE_SERVICE,
            Response::HTTP_CREATED
        );
        $manager->persist($log4);

        $log5 = $this->createLog(
            Carbon::parse('2024-01-02 03:22:13'),
            'log5',
            self::USER_SERVICE,
            Response::HTTP_BAD_REQUEST
        );
        $manager->persist($log5);

        $manager->flush();
    }

    private function createLog(
        DateTimeInterface $requestDate,
        string $hashKey,
        string $serviceName,
        int $statusCode
    ): Log {
        return (new Log())
            ->setServiceName($serviceName)
            ->setHash(\md5($hashKey))
            ->setProtocol('HTTP 1.1')
            ->setMethod('POST')
            ->setServiceUrl('/user')
            ->setRequestDate($requestDate)
            ->setStatusCode($statusCode);
    }
}
