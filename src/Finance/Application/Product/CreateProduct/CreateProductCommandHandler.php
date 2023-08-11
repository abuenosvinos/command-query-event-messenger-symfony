<?php

namespace App\Finance\Application\Product\CreateProduct;

use App\Event\Domain\Bus\Command\CommandHandler;
use App\Event\Domain\Bus\Event\EventBus;
use App\Event\Domain\Bus\Event\EventOptions;
use App\Finance\Domain\Entity\Product;
use App\Finance\Domain\Event\Product\ProductCreated;
use App\Finance\Domain\Repository\ProductRepository;

class CreateProductCommandHandler implements CommandHandler
{
    public function __construct(
        private ProductRepository $productRepository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(CreateProductCommand $command): void
    {
        $product = new Product();
        $product->setCode($command->code());
        $product->setName($command->name());
        $product->setPurchasePrice($command->purchasePrice());
        $product->setSalePrice($command->salePrice());

        $this->productRepository->save($product);

        $this->eventBus->notify(
            new ProductCreated(
                $product->getId(),
                $product->getCode(),
                $product->getName(),
                $product->getPurchasePrice(),
                $product->getSalePrice()
            ),
            /*EventOptions::fromData(['transport' => ['async_low']])*/
        );
    }
}
