<?php

declare(strict_types=1);

namespace App\Data\Domain\Entity;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\EmailAddress;

class User extends AggregateRoot
{
    private int $id;
    private EmailAddress $email;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function setEmail(EmailAddress $email): void
    {
        $this->email = $email;
    }
}
