<?php

namespace App\Finance\Domain\Event\Product;

use App\Event\Domain\Bus\Event\Event;
use App\Shared\Domain\ValueObject\Uuid;

class ProductCreated extends Event
{
    public function __construct(
        private readonly Uuid $id,
        private readonly string $code,
        private readonly string $name,
        private readonly int $purchasePrice,
        private readonly int $salePrice
    ) {
        parent::__construct([
            'id' => $this->id->value(),
            'code' => $this->code,
            'name' => $this->name,
            'purchasePrice' => $this->purchasePrice,
            'salePrice' => $this->salePrice,
        ]);
    }

    public function id(): Uuid
    {
        return $this->id;
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
