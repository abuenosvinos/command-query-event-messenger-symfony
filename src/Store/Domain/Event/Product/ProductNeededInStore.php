<?php

namespace App\Store\Domain\Event\Product;

use App\Event\Domain\Bus\Event\Event;

class ProductNeededInStore extends Event
{
    public function __construct(
        private readonly string $code,
        private readonly int $quantity
    ) {
        parent::__construct([
            'code' => $this->code,
            'quantity' => $this->quantity
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
}
