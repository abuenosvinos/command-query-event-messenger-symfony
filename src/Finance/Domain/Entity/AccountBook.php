<?php

namespace App\Finance\Domain\Entity;

use App\Finance\Domain\ValueObject\Operation;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Trait\Timestampable;
use App\Shared\Domain\ValueObject\Uuid;

class AccountBook extends AggregateRoot
{
    use Timestampable;

    private Uuid $id;
    private Operation $operation;
    private Product $product;
    private int $quantity;
    private int $price;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }

    public function setOperation(Operation $operation): void
    {
        $this->operation = $operation;
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

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}
