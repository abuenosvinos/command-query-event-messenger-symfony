<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus\Query;

use App\Event\Domain\Bus\Request;

class Query extends Request
{
    public function requestType(): string
    {
        return 'query';
    }
}
