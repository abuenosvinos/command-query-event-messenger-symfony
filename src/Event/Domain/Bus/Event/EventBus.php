<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus\Event;

interface EventBus
{
    public function notify(Event $event, ?EventOptions $options = null): void;
}
