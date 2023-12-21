<?php

namespace App\Finance\Application\Product\ListProducts;

use App\Event\Domain\Bus\Query\QueryHandler;
use App\Finance\Domain\Entity\Product;
use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Finance\Domain\Repository\ProductRepository;

class ListProductsQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    /**
     * @return Paginator<Product>
     */
    public function __invoke(ListProductsQuery $query): Paginator
    {
        return $this->productRepository->search(Criteria::fromScratch());
    }
}
