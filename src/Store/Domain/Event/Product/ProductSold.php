<?php

namespace App\Store\Domain\Event\Product;

use App\Event\Domain\Bus\Event\Event;

class ProductSold extends Event
{
    public function __construct(
        private readonly string $code,
        private readonly int $quantity,
        private readonly int $price
    ) {
        parent::__construct([
            'code' => $this->code,
            'quantity' => $this->quantity,
            'price' => $this->price
        ]);
    }

    public function code(): string
    {
        return $this->code;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function price(): int
    {
        return $this->price;
    }
}
