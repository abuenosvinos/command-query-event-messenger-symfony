<?php

declare(strict_types=1);

namespace App\Store\Application\Product\ProductSent;

use App\Event\Domain\Bus\Event\EventHandler;
use App\Store\Domain\Entity\Product;
use App\Store\Domain\Repository\ProductRepository;
use App\Warehouse\Domain\Event\Product\ProductSent;

final class ProductSentEventHandler implements EventHandler
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function __invoke(ProductSent $event): void
    {
        /** @var Product $product */
        $product = $this->productRepository->findByCode($event->code());

        $product->addQuantity($event->quantity());

        $this->productRepository->save($product);
    }
}
