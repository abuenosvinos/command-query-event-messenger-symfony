<?php

namespace App\Store\Application\Product\SaleProduct;

use App\Event\Domain\Bus\Command\Command;

class SaleProductCommand extends Command
{
    public function __construct(
        private readonly string $code,
        private readonly int $quantity
    ) {
        parent::__construct();
    }

    public function code(): string
    {
        return $this->code;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
