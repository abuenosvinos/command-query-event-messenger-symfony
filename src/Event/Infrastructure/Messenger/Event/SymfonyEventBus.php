<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Messenger\Event;

use App\Event\Domain\Bus\Event\Event;
use App\Event\Domain\Bus\Event\EventBus;
use App\Event\Domain\Bus\Event\EventConfiguration;
use App\Event\Domain\Bus\Event\EventNotRegisteredError;
use App\Event\Domain\Bus\Event\EventOptions;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Throwable;

class SymfonyEventBus implements EventBus
{
    public function __construct(
        private readonly MessageBusInterface $eventBus,
        private readonly EventConfiguration $eventConfiguration
    ) {
    }

    /**
     * @throws Throwable
     */
    public function notify(Event $event, ?EventOptions $options = null): void
    {
        try {
            $envelope = new Envelope($event, $this->eventConfiguration->getConfiguration($event, $options));

            $this->eventBus->dispatch(($envelope)
                ->with(new DispatchAfterCurrentBusStamp()));
        } catch (NoHandlerForMessageException) {
            throw new EventNotRegisteredError($event);
        } catch (HandlerFailedException $exception) {
            ($exception->getPrevious()) ? throw $exception->getPrevious() : throw $exception;
        }
    }
}
