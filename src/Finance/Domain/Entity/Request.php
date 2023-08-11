<?php

namespace App\Finance\Domain\Entity;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Trait\Timestampable;
use App\Shared\Domain\ValueObject\Uuid;
use App\Finance\Domain\ValueObject\StatusRequest;

class Request extends AggregateRoot
{
    use Timestampable;

    private Uuid $id;
    private Product $product;
    private int $quantity = 0;
    private StatusRequest $status;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function addQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function removeQuantity(int $quantity): void
    {
        $this->quantity -= $quantity;
    }

    public function getStatus(): StatusRequest
    {
        return $this->status;
    }

    public function setStatus(StatusRequest $status): void
    {
        $this->status = $status;
    }
}
