<?php

declare(strict_types=1);

namespace App\Finance\Infrastructure\Persistence\Doctrine;

use App\Finance\Domain\Entity\AccountBook;
use App\Finance\Domain\Repository\AccountBookRepository;
use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Order;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use ArrayIterator;

class DoctrineAccountBookRepository extends DoctrineRepository implements AccountBookRepository
{
    public function save(AccountBook $accountBook): void
    {
        $this->persistAndFlush($accountBook);
    }

    public function remove(AccountBook $accountBook): void
    {
        $this->removeAndFlush($accountBook);
    }

    public function findById(Uuid $id): ?AccountBook
    {
        /** @var AccountBook $object */
        $object = $this->repository(AccountBook::class)->find($id->value());

        return $object;
    }

    public function search(Criteria $criteria): Paginator
    {
        if (!$criteria->hasOrder()) {
            $criteria = $criteria->setOrder(Order::fromValues('createdAt', 'asc'));
        }

        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        $results = $this->repository(AccountBook::class)->matching($doctrineCriteria);
        $total = $results->count();

        return new Paginator(
            new ArrayIterator($results->toArray()),
            $total,
            $criteria->offset(),
            $criteria->limit()
        );
    }
}
