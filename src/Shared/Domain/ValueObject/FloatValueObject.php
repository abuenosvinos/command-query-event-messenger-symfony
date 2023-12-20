<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract class FloatValueObject
{
    final protected function __construct(protected float $value)
    {
    }

    public function value(): float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value();
    }

    public static function create(float $value): static
    {
        return new static($value);
    }
}
