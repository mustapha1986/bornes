<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'stations')]
class Station
{
    public const STATUS_AVAILABLE = 'disponible';
    public const STATUS_CHARGING = 'en_charge';
    public const STATUS_OFFLINE = 'en_panne';

    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'int')]
    private int $x;

    #[MongoDB\Field(type: 'int')]
    private int $y;

    #[MongoDB\Field(type: 'string')]
    private string $status = self::STATUS_AVAILABLE;

    #[MongoDB\Field(type: 'date')]
    private \DateTimeImmutable $createdAt;

    #[MongoDB\Field(type: 'date')]
    private \DateTimeImmutable $updatedAt;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->touch();
    }

    public function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'x' => $this->x,
            'y' => $this->y,
            'status' => $this->status,
            'updatedAt' => $this->updatedAt->format(DATE_ATOM),
        ];
    }
}