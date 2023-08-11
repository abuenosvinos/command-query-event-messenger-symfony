<?php

declare(strict_types=1);

namespace App\Store\Application\Product\ProductCreated;

use App\Event\Domain\Bus\Event\EventBus;
use App\Event\Domain\Bus\Event\EventHandler;
use App\Finance\Domain\Event\Product\ProductCreated;
use App\Store\Domain\Entity\Product;
use App\Store\Domain\Event\Product\ProductAdded;
use App\Store\Domain\Repository\ProductRepository;

final class ProductCreatedEventHandler implements EventHandler
{
    public function __construct(
        private ProductRepository $productRepository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(ProductCreated $event): void
    {
        $product = new Product();
        $product->setCode($event->code());
        $product->setPrice($event->salePrice());

        $this->productRepository->save($product);

        $this->eventBus->notify(
            new ProductAdded($event->code())
        );
    }
}
