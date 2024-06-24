<?php
declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\UuidGenerator;
use Carbon\Carbon;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    #[ORM\Column(name: "created_at", type: "datetime")]
    protected ?DateTimeInterface $createdAt = null;

    #[ORM\Id]
    #[ORM\Column(type: "guid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected ?string $id = null;

    #[ORM\Column(name: "updated_at", type: "datetime")]
    protected ?DateTimeInterface $updatedAt = null;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }


    #[ORM\PrePersist]
    public function setCreatedAt(): AbstractEntity
    {
        $this->createdAt = Carbon::now('UTC');

        return $this;
    }

    public function setId(string $id): AbstractEntity
    {
        $this->id = $id;

        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): AbstractEntity
    {
        $this->updatedAt = Carbon::now('UTC');

        return $this;
    }
}
