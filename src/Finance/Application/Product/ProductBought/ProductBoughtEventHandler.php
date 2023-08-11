<?php

declare(strict_types=1);

namespace App\Finance\Application\Product\ProductBought;

use App\Event\Domain\Bus\Event\EventHandler;
use App\Finance\Domain\Entity\Request;
use App\Finance\Domain\Event\Product\ProductBought;
use App\Finance\Domain\Repository\ProductRepository;
use App\Finance\Domain\Repository\RequestRepository;
use App\Finance\Domain\ValueObject\StatusRequest;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter;

final class ProductBoughtEventHandler implements EventHandler
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly RequestRepository $requestRepository
    ) {
    }

    public function __invoke(ProductBought $event): void
    {
        $product = $this->productRepository->findByCode($event->code());

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
            $request->setStatus(StatusRequest::sent());
            $this->requestRepository->save($request);
        }
    }
}
