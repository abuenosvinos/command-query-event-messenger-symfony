<?php

declare(strict_types=1);

namespace App\Data\Application\CaptureEvents;

use App\Data\Domain\Event\LogBook;
use App\Event\Domain\Bus\Event\Event;
use App\Event\Domain\Bus\Event\EventBus;
use App\Event\Domain\Bus\Event\EventHandler;

final class AllEventHandler implements EventHandler
{
    public function __construct(private readonly EventBus $eventBus)
    {
    }

    public function __invoke(Event $event): void
    {
        if ($event instanceof LogBook) {
            return;
        }

        $this->eventBus->notify(
            new LogBook(get_class($event), null, null, null, $event->data())
        );
    }
}
