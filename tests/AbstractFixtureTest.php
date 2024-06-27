<?php
declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractFixtureTest extends WebTestCase
{
    protected ?EntityManager $entityManager;

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
