<?php

namespace App\Finance\Application\AccountBook\ListAccountsBook;

use App\Event\Domain\Bus\Query\QueryHandler;
use App\Finance\Domain\Repository\AccountBookRepository;
use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;

class ListAccountsBookQueryHandler implements QueryHandler
{
    public function __construct(
        private AccountBookRepository $accountBookRepository
    ) {
    }

    public function __invoke(ListAccountsBookQuery $query): Paginator
    {
        return $this->accountBookRepository->search(Criteria::fromScratch());
    }
}
