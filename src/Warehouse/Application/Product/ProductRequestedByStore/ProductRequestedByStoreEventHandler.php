<?php

declare(strict_types=1);

namespace App\Warehouse\Application\Product\ProductRequestedByStore;

use App\Event\Domain\Bus\Event\EventBus;
use App\Event\Domain\Bus\Event\EventHandler;
use App\Store\Domain\Event\Product\ProductNeededInStore;
use App\Warehouse\Domain\Entity\Request;
use App\Warehouse\Domain\Event\Product\ProductNeededInWarehouse;
use App\Warehouse\Domain\Event\Product\ProductSent;
use App\Warehouse\Domain\Repository\ProductRepository;
use App\Warehouse\Domain\Repository\RequestRepository;
use App\Warehouse\Domain\ValueObject\StatusRequest;

final class ProductRequestedByStoreEventHandler implements EventHandler
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly RequestRepository $requestRepository,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(ProductNeededInStore $event): void
    {
        $product = $this->productRepository->findByCode($event->code());

        if (!$product) {
            throw new \DomainException("No product found");
        }

        $request = new Request();
        $request->setProduct($product);
        $request->setQuantity($event->quantity());

        if ($product->getQuantity() > $event->quantity()) {
            $product->removeQuantity($event->quantity());
            $this->productRepository->save($product);

            $request->setStatus(StatusRequest::sent());
            $this->requestRepository->save($request);

            $this->eventBus->notify(
                new ProductSent($event->code(), $event->quantity())
            );
        } else {
            $request->setStatus(StatusRequest::pending());
            $this->requestRepository->save($request);

            $this->eventBus->notify(
                new ProductNeededInWarehouse($event->code(), $event->quantity())
            );
        }
    }
}
