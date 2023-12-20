<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Messenger\Command;

use App\Event\Domain\Bus\Command\Command;
use App\Event\Domain\Bus\Command\CommandBus;
use App\Event\Domain\Bus\Command\CommandNotRegisteredError;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

class SymfonyCommandBus implements CommandBus
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    /**
     * @throws Throwable
     */
    public function dispatch(Command $command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredError($command);
        } catch (HandlerFailedException $exception) {
            ($exception->getPrevious()) ? throw $exception->getPrevious() : throw $exception;
        }
    }
}
