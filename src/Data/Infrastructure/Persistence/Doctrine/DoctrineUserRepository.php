<?php

declare(strict_types=1);

namespace App\Data\Infrastructure\Persistence\Doctrine;

use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Order;
use App\Shared\Domain\ValueObject\EmailAddress;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Data\Domain\Entity\User;
use App\Data\Domain\Repository\UserRepository;
use ArrayIterator;

class DoctrineUserRepository extends DoctrineRepository implements UserRepository
{
    public function save(User $user): void
    {
        $this->persistAndFlush($user);
    }

    public function remove(User $user): void
    {
        $this->removeAndFlush($user);
    }

    public function findByEmail(EmailAddress $email): ?User
    {
        /** @var User $user */
        $user = $this->repository(User::class)->findOneBy(['email.value' => $email->value()]);

        return $user;
    }

    public function findById(int $id): ?User
    {
        /** @var User $user */
        $user = $this->repository(User::class)->find($id);

        return $user;
    }

    public function search(Criteria $criteria): Paginator
    {
        if (!$criteria->hasOrder()) {
            $criteria = $criteria->setOrder(Order::fromValues('createdAt', 'asc'));
        }

        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        $results = $this->repository(User::class)->matching($doctrineCriteria);
        $total = $results->count();

        return new Paginator(
            new ArrayIterator($results->toArray()),
            $total,
            $criteria->offset(),
            $criteria->limit()
        );
    }
}
