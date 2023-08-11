<?php

declare(strict_types=1);

namespace App\Warehouse\Domain\Repository;

use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\ValueObject\Uuid;
use App\Warehouse\Domain\Entity\Request;

interface RequestRepository
{
    public function save(Request $request): void;

    public function remove(Request $request): void;

    public function findById(Uuid $id): ?Request;

    public function search(Criteria $criteria): Paginator;
}
