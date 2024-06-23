<?php
declare(strict_types=1);

namespace App\Service\Log;

use App\Repository\Interface\LogRepositoryInterface;
use App\Service\FileReader\Interface\FileReaderInterface;
use App\Service\FileReader\Interface\StorageDriverInterface;
use App\Service\Log\Filter\LogFilter;
use App\Service\Log\Interface\LogServiceInterface;

final class LogService implements LogServiceInterface
{
    public function __construct(
        private readonly FileReaderInterface $fileReader,
        private readonly LogRepositoryInterface $logRepository
    ) {
    }

    public function filter(array $criteria): int
    {
        return $this->logRepository->countByCriteria(new LogFilter($criteria));
    }

    public function populateLogsFromFileStream(array $settings): void
    {
        $file = $settings['file'];
        $storage = $settings['storage'] ?? StorageDriverInterface::STORAGE_TYPE_LOCAL;
        $linesPerStream = $settings['lines'] ?? self::LINES_PER_STREAM;

        $this->fileReader->read($file, $storage, $linesPerStream);
    }
}
