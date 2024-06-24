<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\LogRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: LogRepository::class)]
#[ORM\Table(name: "logs")]
#[HasLifecycleCallbacks]
class Log extends AbstractEntity
{
    #[ORM\Column(name: "method", type: "string", length: 50)]
    private string $method;

    #[ORM\Column(name: "protocol", type: "string", length: 50)]
    private string $protocol;

    #[ORM\Column(name: "request_date", type: "datetime")]
    private DateTimeInterface $requestDate;

    #[ORM\Column(name: "service_name", type: "string", length: 255)]
    private string $serviceName;

    #[ORM\Column(name: "service_url", type: "string", length: 255)]
    private string $serviceUrl;

    /**
     * @ORM\Column(name"status_code", type="integer")
     */
    #[ORM\Column(name: "status_code", type: "integer")]
    private int $statusCode;

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function getRequestDate(): DateTimeInterface
    {
        return $this->requestDate;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getServiceUrl(): string
    {
        return $this->serviceUrl;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setMethod(string $method): Log
    {
        $this->method = $method;

        return $this;
    }

    public function setProtocol(string $protocol): Log
    {
        $this->protocol = $protocol;

        return $this;
    }

    public function setRequestDate(DateTimeInterface $requestDate): Log
    {
        $this->requestDate = $requestDate;

        return $this;
    }

    public function setServiceName(string $serviceName): Log
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function setServiceUrl(string $serviceUrl): Log
    {
        $this->serviceUrl = $serviceUrl;

        return $this;
    }

    public function setStatusCode(int $statusCode): Log
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
