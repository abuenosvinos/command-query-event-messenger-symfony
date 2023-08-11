<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Messenger\Event;

use App\Event\Domain\Bus\Event\DomainEventPublisher;
use App\Event\Domain\Bus\Event\Event;

class SymfonyDomainEventPublisher implements DomainEventPublisher
{
    private array $events = [];

    public function publish(Event ...$events): void
    {
        $this->events = array_merge($this->events, $events);
    }

    public function release(): array
    {
        $events       = $this->events;
        $this->events = [];

        return $events;
    }
}
