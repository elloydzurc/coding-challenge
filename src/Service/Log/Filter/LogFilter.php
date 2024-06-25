<?php
declare(strict_types=1);

namespace App\Service\Log\Filter;

use Carbon\Carbon;
use DateTimeInterface;

class LogFilter
{
    private ?string $endDate = null;

    private mixed $serviceName = null;

    private ?string $startDate = null;

    private ?string $statusCode = null;

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

        return Carbon::parse($this->endDate, 'UTC');
    }

    public function getServiceName(): ?array
    {
        return \is_array($this->serviceName) ? $this->serviceName : \explode(',', $this->serviceName);
    }

    public function getStartDate(): ?DateTimeInterface
    {
        if ($this->startDate === null) {
            return null;
        }

        return Carbon::parse($this->startDate, 'UTC');
    }

    public function getStatusCode(): ?string
    {
        return $this->statusCode;
    }
}
