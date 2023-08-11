<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus\Event;

interface DomainEventPublisher
{
    public function publish(Event ...$events): void;

    public function release(): array;
}
