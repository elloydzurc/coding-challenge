<?php
declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Entity\Log;
use App\Messenger\Message\CreateLogFromFileMessage;
use App\Repository\Interface\LogRepositoryInterface;
use Carbon\Carbon;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateLogFromFileHandler
{
    public function __construct(private readonly LogRepositoryInterface $logRepository)
    {
    }

    public function __invoke(CreateLogFromFileMessage $message): void
    {
        $hash = \md5($message->getContent());

        if ($this->logRepository->findByHash($hash)) {
            return;
        }

        $this->logRepository->createFromEntity(
            $this->createEntityFromContent($message->getContent(), $hash)
        );
    }

    private function createEntityFromContent(string $content, string $hash): Log
    {
        $pattern = '/([A-Z-]+) - - \[(.*?)] "([A-Z]+) ([^ ]+) (HTTP\/[0-9.]+)" ([0-9]+)/';
        preg_match($pattern, $content, $matches);

        [,$serviceName, $requestDate, $method, $serviceUrl, $protocol, $statusCode] = $matches;

        $log = new Log();
        $log->setServiceName($serviceName)
            ->setHash($hash)
            ->setMethod($method)
            ->setRequestDate(Carbon::parse($requestDate, 'UTC'))
            ->setServiceUrl($serviceUrl)
            ->setProtocol($protocol)
            ->setStatusCode((int)$statusCode);

        return $log;
    }
}
