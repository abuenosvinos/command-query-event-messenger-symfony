<?php

declare(strict_types=1);

namespace App\Store\Application\Product\ProductAdded;

use App\Event\Domain\Bus\Event\EventBus;
use App\Event\Domain\Bus\Event\EventHandler;
use App\Event\Domain\Bus\Query\QueryBus;
use App\Store\Application\Product\FindProduct\FindProductQuery;
use App\Store\Domain\Entity\Product;
use App\Store\Domain\Event\Product\ProductAdded;
use App\Store\Domain\Event\Product\ProductNeededInStore;
use App\Store\Domain\Event\Product\ProductSold;

final class ProductAddedEventHandler implements EventHandler
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(ProductAdded|ProductSold $event): void
    {
        /** @var Product $product */
        $product = $this->queryBus->ask(new FindProductQuery($event->code()));

        if ($product->getQuantity() <= 5) {
            $this->eventBus->notify(
                new ProductNeededInStore($event->code(), 10)
            );
        }
    }
}
