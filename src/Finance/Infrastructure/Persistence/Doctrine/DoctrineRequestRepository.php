<?php

declare(strict_types=1);

namespace App\Finance\Infrastructure\Persistence\Doctrine;

use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Order;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Finance\Domain\Entity\Request;
use App\Finance\Domain\Repository\RequestRepository;
use ArrayIterator;

class DoctrineRequestRepository extends DoctrineRepository implements RequestRepository
{
    public function save(Request $request): void
    {
        $this->persistAndFlush($request);
    }

    public function remove(Request $request): void
    {
        $this->removeAndFlush($request);
    }

    public function findById(Uuid $id): ?Request
    {
        /** @var Request $item */
        $item = $this->repository(Request::class)->find($id->value());

        return $item;
    }

    /**
     * @return Paginator<Request>
     */
    public function search(Criteria $criteria): Paginator
    {
        if (!$criteria->hasOrder()) {
            $criteria = $criteria->setOrder(Order::fromValues('createdAt', 'asc'));
        }

        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        $results = $this->repository(Request::class)->matching($doctrineCriteria);
        $total = $results->count();

        return new Paginator(
            new ArrayIterator($results->toArray()),
            $total,
            $criteria->offset(),
            $criteria->limit()
        );
    }
}
