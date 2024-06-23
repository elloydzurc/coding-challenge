<?php
declare(strict_types=1);

namespace App\Service\Log\Interface;

interface LogServiceInterface
{
    public const int LINES_PER_STREAM = 20;

    public function filter(array $criteria): int;

    public function populateLogsFromFileStream(array $settings): void;
}
