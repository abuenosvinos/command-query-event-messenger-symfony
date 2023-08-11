<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\NotValidEmailAddressException;

class EmailAddress extends StringValueObject
{
    public function __construct(protected string $value)
    {
        $this->ensureIsValid($value);

        parent::__construct($value);
    }

    private function ensureIsValid(string $email): void
    {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new NotValidEmailAddressException(sprintf('The email <%s> is not well formatted', $email));
        }
    }
}
