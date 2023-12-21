<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Event\Domain\Bus\Event\Event;

abstract class AggregateRoot
{
    /** @var array<Event> $domainEvents */
    private array $domainEvents = [];

    /**
     * @return array<Event>
     */
    final public function pullDomainEvents(): array
    {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function record(Event $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
