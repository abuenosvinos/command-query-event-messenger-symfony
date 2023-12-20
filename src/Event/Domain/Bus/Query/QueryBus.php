<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus\Query;

interface QueryBus
{
    public function ask(Query $query): mixed;//: ?Response;
}
