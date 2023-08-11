<?php

namespace App\Store\Application\Product\FindProduct;

use App\Event\Domain\Bus\Query\Query;

class FindProductQuery extends Query
{
    public function __construct(private readonly string $code)
    {
        parent::__construct();
    }

    public function code(): string
    {
        return $this->code;
    }
}
