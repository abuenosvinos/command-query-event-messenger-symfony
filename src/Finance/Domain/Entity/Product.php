<?php

namespace App\Finance\Domain\Entity;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Trait\Sluggable;
use App\Shared\Domain\Trait\Timestampable;
use App\Shared\Domain\ValueObject\Uuid;

class Product extends AggregateRoot
{
    use Sluggable;
    use Timestampable;

    private Uuid $id;
    private string $code;
    private string $name;
    private int $purchasePrice;
    private int $salePrice;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPurchasePrice(): int
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice(int $purchasePrice): void
    {
        $this->purchasePrice = $purchasePrice;
    }

    public function getSalePrice(): int
    {
        return $this->salePrice;
    }

    public function setSalePrice(int $salePrice): void
    {
        $this->salePrice = $salePrice;
    }
}
