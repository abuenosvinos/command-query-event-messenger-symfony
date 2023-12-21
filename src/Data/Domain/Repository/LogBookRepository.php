<?php

declare(strict_types=1);

namespace App\Data\Domain\Repository;

use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Data\Domain\Entity\LogBook;

interface LogBookRepository
{
    public function save(LogBook $logBook): void;

    public function remove(LogBook $logBook): void;

    public function findById(int $id): ?LogBook;

    /**
     * @return array<LogBook>
     */
    public function findDifferentAction(): array;

    /**
     * @return array<LogBook>
     */
    public function findDifferentObjectType(): array;

    /**
     * @return Paginator<LogBook>
     */
    public function search(Criteria $criteria): Paginator;
}
