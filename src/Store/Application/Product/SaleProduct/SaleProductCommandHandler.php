<?php

namespace App\Store\Application\Product\SaleProduct;

use App\Event\Domain\Bus\Command\CommandHandler;
use App\Event\Domain\Bus\Event\EventBus;
use App\Event\Domain\Bus\Query\QueryBus;
use App\Store\Application\Product\FindProduct\FindProductQuery;
use App\Store\Domain\Entity\Product;
use App\Store\Domain\Event\Product\ProductSold;
use App\Store\Domain\Exception\NotEnoughItemProductsException;
use App\Store\Domain\Repository\ProductRepository;

class SaleProductCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly QueryBus $queryBus,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(SaleProductCommand $command): void
    {
        /** @var Product $product */
        $product = $this->queryBus->ask(new FindProductQuery($command->code()));

        if ($product->getQuantity() < $command->quantity()) {
            throw new NotEnoughItemProductsException($product);
        }

        $product->removeQuantity($command->quantity());
        $this->productRepository->save($product);

        $this->eventBus->notify(
            new ProductSold(
                $command->code(),
                $command->quantity(),
                $product->getPrice()
            )
        );
    }
}
