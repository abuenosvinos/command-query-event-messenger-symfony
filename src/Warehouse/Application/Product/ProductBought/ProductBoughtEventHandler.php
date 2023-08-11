<?php

declare(strict_types=1);

namespace App\Warehouse\Application\Product\ProductBought;

use App\Event\Domain\Bus\Event\EventBus;
use App\Event\Domain\Bus\Event\EventHandler;
use App\Warehouse\Domain\Entity\Request;
use App\Finance\Domain\Event\Product\ProductBought;
use App\Warehouse\Domain\Event\Product\ProductSent;
use App\Warehouse\Domain\Repository\RequestRepository;
use App\Warehouse\Domain\ValueObject\StatusRequest;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter;
use App\Warehouse\Domain\Entity\Product;
use App\Warehouse\Domain\Repository\ProductRepository;

final class ProductBoughtEventHandler implements EventHandler
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly RequestRepository $requestRepository,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(ProductBought $event): void
    {
        /** @var Product $product */
        $product = $this->productRepository->findByCode($event->code());
        $product->addQuantity($event->quantity());

        $criteria = Criteria::fromFilters(
            [
                Filter::fromValues([
                    'field' => 'product',
                    'operator' => '=',
                    'value' => $product
                ]),
                Filter::fromValues([
                    'field' => 'status.value',
                    'operator' => '=',
                    'value' => StatusRequest::PENDING
                ])
            ]
        );

        $requests = $this->requestRepository->search($criteria);
        /** @var Request $request */
        foreach ($requests->results() as $request) {
            if ($request->getQuantity() < $product->getQuantity()) {
                $product->removeQuantity($request->getQuantity());
                $request->setStatus(StatusRequest::sent());
                $this->requestRepository->save($request);

                $this->eventBus->notify(
                    new ProductSent($event->code(), $request->getQuantity())
                );
            }
        }

        $this->productRepository->save($product);
    }
}
