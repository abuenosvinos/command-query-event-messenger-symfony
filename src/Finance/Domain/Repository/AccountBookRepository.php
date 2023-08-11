<?php

declare(strict_types=1);

namespace App\Finance\Domain\Repository;

use App\Finance\Domain\Entity\AccountBook;
use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\ValueObject\Uuid;

interface AccountBookRepository
{
    public function save(AccountBook $accountBook): void;

    public function remove(AccountBook $accountBook): void;

    public function findById(Uuid $id): ?AccountBook;

    public function search(Criteria $criteria): Paginator;
}
