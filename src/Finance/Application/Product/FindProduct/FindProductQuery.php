<?php

namespace App\Finance\Application\Product\FindProduct;

use App\Event\Domain\Bus\Query\Query;

class FindProductQuery extends Query
{
    public function __construct(private string $code)
    {
        parent::__construct();
    }

    public function code(): string
    {
        return $this->code;
    }
}
