<?php

declare(strict_types=1);

namespace App\Data\Domain\Repository;

use App\Shared\Application\Paginator;
use App\Shared\Domain\Criteria\Criteria;
use App\Data\Domain\Entity\User;
use App\Shared\Domain\ValueObject\EmailAddress;

interface UserRepository
{
    public function save(User $user): void;

    public function remove(User $user): void;

    public function findByEmail(EmailAddress $email): ?User;

    public function findById(int $id): ?User;

    public function search(Criteria $criteria): Paginator;
}
