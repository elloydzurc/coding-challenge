<?php
declare(strict_types=1);

namespace App\Service\Log\Interface;

interface LogServiceInterface
{
    public function filter(array $criteria): int;

    public function populateLogsFromFileStream(string $file, ?string $storage = null): int;
}
