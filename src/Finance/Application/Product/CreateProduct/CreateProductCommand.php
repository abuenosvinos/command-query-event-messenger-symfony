<?php

namespace App\Finance\Application\Product\CreateProduct;

use App\Event\Domain\Bus\Command\Command;

class CreateProductCommand extends Command
{
    public function __construct(
        private readonly string $code,
        private readonly string $name,
        private readonly int $purchasePrice,
        private readonly int $salePrice
    ) {
        parent::__construct();
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function purchasePrice(): int
    {
        return $this->purchasePrice;
    }

    public function salePrice(): int
    {
        return $this->salePrice;
    }
}
