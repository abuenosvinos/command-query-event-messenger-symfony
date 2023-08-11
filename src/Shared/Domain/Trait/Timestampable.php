<?php

declare(strict_types=1);

namespace App\Shared\Domain\Trait;

trait Timestampable
{
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function createdAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function updatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
