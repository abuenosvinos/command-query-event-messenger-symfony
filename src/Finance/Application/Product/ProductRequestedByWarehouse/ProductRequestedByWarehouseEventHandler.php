<?php

declare(strict_types=1);

namespace App\Finance\Application\Product\ProductRequestedByWarehouse;

use App\Event\Domain\Bus\Command\CommandBus;
use App\Event\Domain\Bus\Event\EventHandler;
use App\Finance\Application\Product\BuyProduct\BuyProductCommand;
use App\Finance\Domain\Repository\ProductRepository;
use App\Finance\Domain\Repository\RequestRepository;
use App\Finance\Domain\ValueObject\StatusRequest;
use App\Finance\Domain\Entity\Request;
use App\Warehouse\Domain\Event\Product\ProductNeededInWarehouse;

final class ProductRequestedByWarehouseEventHandler implements EventHandler
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly RequestRepository $requestRepository,
        private readonly CommandBus $commandBus
    ) {
    }

    public function __invoke(ProductNeededInWarehouse $event): void
    {
        $product = $this->productRepository->findByCode($event->code());

        if (!$product) {
            throw new \DomainException("No product found");
        }

        $request = new Request();
        $request->setProduct($product);
        $request->setQuantity($event->quantity());
        $request->setStatus(StatusRequest::pending());
        $this->requestRepository->save($request);

        // TODO; Calculate the quantity to buy
        $quantityToBuy = 50 + $event->quantity();

        $this->commandBus->dispatch(
            new BuyProductCommand($event->code(), $quantityToBuy)
        );
    }
}
