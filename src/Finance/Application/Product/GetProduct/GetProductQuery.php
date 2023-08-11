<?php

namespace App\Finance\Application\Product\GetProduct;

use App\Event\Domain\Bus\Query\Query;

class GetProductQuery extends Query
{
    public function __construct(private string $uuid)
    {
        parent::__construct();
    }

    public function uuid(): string
    {
        return $this->uuid;
    }
}
