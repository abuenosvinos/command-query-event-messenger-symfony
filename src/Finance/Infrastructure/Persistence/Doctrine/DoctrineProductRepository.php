<?php

declare(strict_types=1);

namespace App\Finance\Infrastructure\Persistence\Doctrine;

use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Order;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Finance\Domain\Entity\Product;
use App\Finance\Domain\Repository\ProductRepository;
use ArrayIterator;

class DoctrineProductRepository extends DoctrineRepository implements ProductRepository
{
    public function save(Product $product): void
    {
        $this->persistAndFlush($product);
    }

    public function remove(Product $product): void
    {
        $this->removeAndFlush($product);
    }

    public function findByCode(string $code): ?Product
    {
        /** @var Product $object */
        $object = $this->repository(Product::class)->findOneBy(['code' => $code]);

        return $object;
    }

    public function findById(Uuid $id): ?Product
    {
        /** @var Product $object */
        $object = $this->repository(Product::class)->find($id->value());

        return $object;
    }

    /**
     * @return Paginator<Product>
     */
    public function search(Criteria $criteria): Paginator
    {
        if (!$criteria->hasOrder()) {
            $criteria = $criteria->setOrder(Order::fromValues('createdAt', 'asc'));
        }

        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        $results = $this->repository(Product::class)->matching($doctrineCriteria);
        $total = $results->count();

        return new Paginator(
            new ArrayIterator($results->toArray()),
            $total,
            $criteria->offset(),
            $criteria->limit()
        );
    }
}
