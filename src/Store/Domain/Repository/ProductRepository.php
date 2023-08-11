<?php

declare(strict_types=1);

namespace App\Store\Domain\Repository;

use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\ValueObject\Uuid;
use App\Store\Domain\Entity\Product;

interface ProductRepository
{
    public function save(Product $product): void;

    public function remove(Product $product): void;

    public function findByCode(string $code): ?Product;

    public function findById(Uuid $id): ?Product;

    public function search(Criteria $criteria): Paginator;
}
