<?php

namespace App\Store\Application\Product\FindProduct;

use App\Event\Domain\Bus\Query\QueryHandler;
use App\Store\Domain\Entity\Product;
use App\Store\Domain\Repository\ProductRepository;

class FindProductQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function __invoke(FindProductQuery $query): Product
    {
        return $this->productRepository->findByCode($query->code());
    }
}
