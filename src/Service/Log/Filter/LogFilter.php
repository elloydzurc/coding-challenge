<?php
declare(strict_types=1);

namespace App\Service\Log\Filter;

use App\Service\Log\Exception\LogServiceRuntimeException;
use Carbon\Carbon;
use DateTimeInterface;

class LogFilter
{
    private ?string $endDate = null;

    private mixed $serviceName = null;

    private ?string $startDate = null;

    private mixed $statusCode = null;

    public function __construct(private readonly ?array $criteria = null)
    {
        $this->build();
    }

    public function build(): void
    {
        if ($this->criteria === null) {
            return;
        }

        foreach ($this->criteria as $key => $value) {
            if (\property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getEndDate(): ?DateTimeInterface
    {
        if ($this->endDate === null) {
            return null;
        }

        return $this->parseDateTime($this->endDate);
    }

    public function getServiceName(): ?array
    {
        if ($this->serviceName === null) {
            return null;
        }

        return \is_array($this->serviceName) ? $this->serviceName : \explode(',', $this->serviceName);
    }

    public function getStartDate(): ?DateTimeInterface
    {
        if ($this->startDate === null) {
            return null;
        }

        return $this->parseDateTime($this->startDate);
    }

    public function getStatusCode(): ?int
    {
        if (\is_numeric($this->statusCode) === false) {
            throw LogServiceRuntimeException::invalidStatusCodeFormat();
        }

        return (int)$this->statusCode;
    }

    private function parseDateTime(string $dateTime): DateTimeInterface
    {
        try {
            return Carbon::parse($dateTime, 'UTC');
        } catch (\Throwable) {
            throw LogServiceRuntimeException::invalidDatetimeFormat();
        }
    }
}
