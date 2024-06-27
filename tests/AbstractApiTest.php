<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiTest extends AbstractFixtureTest
{
    protected KernelBrowser $client;

    protected function apiRequest(string $method, string $uri, ?array $parameters = null): Response
    {
        $this->client->request($method, $uri , parameters: $parameters ?? []);

        return $this->client->getResponse();
    }
}
