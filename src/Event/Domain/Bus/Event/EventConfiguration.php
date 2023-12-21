<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus\Event;

interface EventConfiguration
{
    /**
     * @return mixed[]
     */
    public function getConfiguration(Event $event, ?EventOptions $eventOptions): array;
}
