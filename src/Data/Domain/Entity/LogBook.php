<?php

declare(strict_types=1);

namespace App\Data\Domain\Entity;

use App\Shared\Domain\Aggregate\AggregateRoot;

class LogBook extends AggregateRoot
{
    private int $id;
    private string $action;
    private ?User $user;
    private ?string $object_type;
    private ?string $object_id;
    private ?\DateTime $occurredOn;
    private ?string $metadata;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getObjectType(): ?string
    {
        return $this->object_type;
    }

    public function setObjectType(?string $object_type): void
    {
        $this->object_type = $object_type;
    }

    public function getObjectId(): ?string
    {
        return $this->object_id;
    }

    public function setObjectId(?string $object_id): void
    {
        $this->object_id = $object_id;
    }

    public function getOccurredOn(): ?\DateTime
    {
        return $this->occurredOn;
    }

    public function setOccurredOn(?\DateTime $occurredOn): void
    {
        $this->occurredOn = $occurredOn;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function setMetadata(?string $metadata): void
    {
        $this->metadata = $metadata;
    }
}
