<?php
declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiTest extends WebTestCase
{
    protected ?EntityManager $entityManager;

    protected KernelBrowser $client;

    protected function apiRequest(string $method, string $uri, ?array $parameters = null): Response
    {
        $this->client->request($method, $uri , parameters: $parameters ?? []);

        return $this->client->getResponse();
    }

    protected function loadFixtures(string $fixturesClassName): void
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $loader = new Loader();
        $loader->addFixture(new $fixturesClassName());

        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
