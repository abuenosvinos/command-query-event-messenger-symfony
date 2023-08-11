<?php

namespace App\Warehouse\Application\Request\ListRequest;

use App\Event\Domain\Bus\Query\QueryHandler;
use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Warehouse\Domain\Repository\RequestRepository;

class ListRequestQueryHandler implements QueryHandler
{
    public function __construct(
        private RequestRepository $requestRepository
    ) {
    }

    public function __invoke(ListRequestQuery $query): Paginator
    {
        return $this->requestRepository->search(Criteria::fromScratch());
    }
}
