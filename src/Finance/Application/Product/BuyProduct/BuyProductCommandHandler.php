<?php

namespace App\Finance\Application\Product\BuyProduct;

use App\Event\Domain\Bus\Command\CommandHandler;
use App\Event\Domain\Bus\Event\EventBus;
use App\Event\Domain\Bus\Query\QueryBus;
use App\Finance\Application\Product\FindProduct\FindProductQuery;
use App\Finance\Domain\Entity\AccountBook;
use App\Finance\Domain\Entity\Product;
use App\Finance\Domain\Event\Product\ProductBought;
use App\Finance\Domain\Repository\AccountBookRepository;
use App\Finance\Domain\ValueObject\Operation;

class BuyProductCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly AccountBookRepository $accountBookRepository,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(BuyProductCommand $command): void
    {
        /** @var Product $product */
        $product = $this->queryBus->ask(new FindProductQuery($command->code()));

        $accountBook = new AccountBook();
        $accountBook->setProduct($product);
        $accountBook->setOperation(Operation::purchase());
        $accountBook->setPrice($product->getPurchasePrice());
        $accountBook->setQuantity($command->quantity());

        $this->accountBookRepository->save($accountBook);

        $this->eventBus->notify(
            new ProductBought(
                $command->code(),
                $command->quantity(),
                $product->getPurchasePrice()
            )
        );
    }
}
