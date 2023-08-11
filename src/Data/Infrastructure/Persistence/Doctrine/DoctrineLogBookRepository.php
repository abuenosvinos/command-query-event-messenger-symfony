<?php

declare(strict_types=1);

namespace App\Data\Infrastructure\Persistence\Doctrine;

use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Data\Domain\Entity\LogBook;
use App\Data\Domain\Repository\LogBookRepository;
use ArrayIterator;

class DoctrineLogBookRepository extends DoctrineRepository implements LogBookRepository
{
    public function save(LogBook $logBook): void
    {
        $this->persistAndFlush($logBook);
    }

    public function remove(LogBook $logBook): void
    {
        $this->removeAndFlush($logBook);
    }

    public function findById(int $id): ?LogBook
    {
        /** @var LogBook $logBook */
        $logBook = $this->repository(LogBook::class)->find($id);

        return $logBook;
    }

    public function findDifferentAction(): array
    {
        $query = $this->repository(LogBook::class)
            ->createQueryBuilder('c')
            ->select('c.action')
            ->groupBy('c.action')
            ->getQuery();

        return $query->execute();
    }

    public function findDifferentObjectType(): array
    {
        $query = $this->repository(LogBook::class)
            ->createQueryBuilder('c')
            ->select('c.object_type')
            ->groupBy('c.object_type')
            ->getQuery();

        return $query->execute();
    }

    public function search(Criteria $criteria): Paginator
    {
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        $results = $this->repository(LogBook::class)->matching($doctrineCriteria);
        $total = $results->count();

        return new Paginator(
            new ArrayIterator($results->toArray()),
            $total,
            $criteria->offset(),
            $criteria->limit()
        );
    }
}
