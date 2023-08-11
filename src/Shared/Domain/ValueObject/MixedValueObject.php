<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract class MixedValueObject
{
    public function __construct(protected mixed $value)
    {
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }

    public static function create(mixed $value): static
    {
        return new static($value);
    }
}
