<?php

namespace App\Finance\Application\Product\BuyProduct;

use App\Event\Domain\Bus\Command\Command;

class BuyProductCommand extends Command
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
