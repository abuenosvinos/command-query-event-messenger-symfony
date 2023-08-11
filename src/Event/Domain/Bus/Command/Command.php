<?php

namespace App\Event\Domain\Bus\Command;

use App\Event\Domain\Bus\Request;

abstract class Command extends Request
{
    public function requestType(): string
    {
        return 'command';
    }
}
