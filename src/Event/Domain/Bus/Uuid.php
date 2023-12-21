<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    final private function __construct(protected string $value)
    {
        $this->ensureIsValidUuid($value);
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw new InvalidArgumentException(
                sprintf('<%s> does not allow the value <%s>.', static::class, $id)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }

    public static function random(): static
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public static function create(string $value): static
    {
        return new static($value);
    }
}
