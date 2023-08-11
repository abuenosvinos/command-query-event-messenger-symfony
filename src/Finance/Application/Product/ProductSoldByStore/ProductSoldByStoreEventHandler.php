<?php

declare(strict_types=1);

namespace App\Finance\Application\Product\ProductSoldByStore;

use App\Event\Domain\Bus\Event\EventHandler;
use App\Event\Domain\Bus\Query\QueryBus;
use App\Finance\Application\Product\FindProduct\FindProductQuery;
use App\Finance\Domain\Entity\AccountBook;
use App\Finance\Domain\Entity\Product;
use App\Finance\Domain\Repository\AccountBookRepository;
use App\Finance\Domain\ValueObject\Operation;
use App\Store\Domain\Event\Product\ProductSold;

final class ProductSoldByStoreEventHandler implements EventHandler
{
    public function __construct(
        private readonly AccountBookRepository $accountBookRepository,
        private readonly QueryBus $queryBus
    ) {
    }

    public function __invoke(ProductSold $event): void
    {
        /** @var Product $product */
        $product = $this->queryBus->ask(new FindProductQuery($event->code()));

        $accountBook = new AccountBook();
        $accountBook->setProduct($product);
        $accountBook->setOperation(Operation::sale());
        $accountBook->setPrice($event->price());
        $accountBook->setQuantity($event->quantity());

        $this->accountBookRepository->save($accountBook);
    }
}
