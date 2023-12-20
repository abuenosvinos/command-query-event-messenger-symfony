<?php

namespace App\Finance\Application\Product\GetProduct;

use App\Event\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\ValueObject\Uuid;
use App\Finance\Domain\Entity\Product;
use App\Finance\Domain\Repository\ProductRepository;

class GetProductQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function __invoke(GetProductQuery $query): ?Product
    {
        return $this->productRepository->findById(Uuid::create($query->uuid()));
    }
}
