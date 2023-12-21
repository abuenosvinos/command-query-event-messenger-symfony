<?php

namespace App\Data\Application\ListLogBook;

use App\Data\Domain\Entity\LogBook;
use App\Event\Domain\Bus\Query\QueryHandler;
use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Data\Domain\Repository\LogBookRepository;

class ListLogBookQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly LogBookRepository $logBookRepository
    ) {
    }

    /**
     * @return Paginator<LogBook>
     */
    public function __invoke(ListLogBookQuery $query): Paginator
    {
        return $this->logBookRepository->search(Criteria::fromScratch());
    }
}
