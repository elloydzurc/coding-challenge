<?php
declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\UuidGenerator;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    #[ORM\Column(name: "created_at", type: "datetime")]
    protected DateTimeInterface $createdAt;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected string $id;

    #[ORM\Column(name: "updated_at", type: "datetime")]
    protected DateTimeInterface $updatedAt;

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): AbstractEntity
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setId(string $id): AbstractEntity
    {
        $this->id = $id;

        return $this;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): AbstractEntity
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
