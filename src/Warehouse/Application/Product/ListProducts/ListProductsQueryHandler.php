<?php

namespace App\Warehouse\Application\Product\ListProducts;

use App\Event\Domain\Bus\Query\QueryHandler;
use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Warehouse\Domain\Repository\ProductRepository;

class ListProductsQueryHandler implements QueryHandler
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function __invoke(ListProductsQuery $query): Paginator
    {
        return $this->productRepository->search(Criteria::fromScratch());
    }
}
