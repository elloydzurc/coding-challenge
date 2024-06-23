<?php
declare(strict_types=1);

namespace App\Service\Log;

use App\Messenger\Message\CreateLogFromFileMessage;
use App\Repository\Interface\LogRepositoryInterface;
use App\Service\FileReader\Interface\FileReaderInterface;
use App\Service\FileReader\Interface\StorageDriverInterface;
use App\Service\Log\Filter\LogFilter;
use App\Service\Log\Interface\LogServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class LogService implements LogServiceInterface
{
    public function __construct(
        private readonly FileReaderInterface $fileReader,
        private readonly LogRepositoryInterface $logRepository,
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function filter(array $criteria): int
    {
        return $this->logRepository->countByCriteria(new LogFilter($criteria));
    }

    /**
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    public function populateLogsFromFileStream(array $settings): void
    {
        $file = $settings['file'];
        $linesPerStream = $settings['lines'] ?? self::LINES_PER_STREAM;
        $storage = $settings['storage'] ?? StorageDriverInterface::STORAGE_TYPE_LOCAL;

        foreach ($this->fileReader->read($file, $storage, $linesPerStream) as $rawLog) {
            $this->messageBus->dispatch(new CreateLogFromFileMessage($rawLog));
        }
    }
}
