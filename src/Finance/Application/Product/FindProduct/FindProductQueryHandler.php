<?php

namespace App\Finance\Application\Product\FindProduct;

use App\Event\Domain\Bus\Query\QueryHandler;
use App\Finance\Domain\Entity\Product;
use App\Finance\Domain\Repository\ProductRepository;

class FindProductQueryHandler implements QueryHandler
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function __invoke(FindProductQuery $query): Product
    {
        return $this->productRepository->findByCode($query->code());
    }
}
