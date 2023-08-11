<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract class StringValueObject
{
    public function __construct(protected string $value)
    {
    }

    public function value(): string
    {
        if (empty($this->value)) {
            return '';
        }
        return $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }

    public static function create(string $value): static
    {
        return new static($value);
    }
}
