<?php

namespace App\Store\Domain\Event\Product;

use App\Event\Domain\Bus\Event\Event;

class ProductAdded extends Event
{
    public function __construct(
        private readonly string $code
    ) {
        parent::__construct([
            'code' => $this->code
        ]);
    }

    public function code(): string
    {
        return $this->code;
    }
}
