<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus\Command;

final class CommandNotRegisteredError extends \DomainException
{
    public function __construct(private readonly Command $command)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'command_bus_not_registered_error';
    }

    protected function errorMessage(): string
    {
        return sprintf('The command <%s> has not been registered', get_class($this->command));
    }
}
