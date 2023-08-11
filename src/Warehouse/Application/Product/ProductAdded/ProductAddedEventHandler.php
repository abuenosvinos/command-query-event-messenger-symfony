<?php

declare(strict_types=1);

namespace App\Warehouse\Application\Product\ProductAdded;

use App\Event\Domain\Bus\Event\EventHandler;
use App\Finance\Domain\Event\Product\ProductCreated;
use App\Warehouse\Domain\Entity\Product;
use App\Warehouse\Domain\Repository\ProductRepository;

final class ProductAddedEventHandler implements EventHandler
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    public function __invoke(ProductCreated $event): void
    {
        $product = new Product();
        $product->setCode($event->code());

        $this->productRepository->save($product);
    }
}
