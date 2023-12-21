<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus\Event;

interface DomainEventPublisher
{
    public function publish(Event ...$events): void;

    /**
     * @return array<Event>
     */
    public function release(): array;
}
