<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use ReflectionClass;

use function Lambdish\Phunctional\reindex;

abstract class Enum
{
    protected static array $cache = [];
    protected static array $DATA = [];

    public function __construct(protected mixed $value)
    {
        $this->ensureIsBetweenAcceptedValues($value);
    }

    abstract protected function throwExceptionForInvalidValue(mixed $value): void;

    public static function __callStatic(string $name, array $args)
    {
        return new static(self::values()[$name]);
    }

    public static function values(): array
    {
        $class = static::class;

        if (!isset(self::$cache[$class])) {
            $reflected           = new ReflectionClass($class);
            self::$cache[$class] = reindex(static::keysFormatter(), $reflected->getConstants());
        }

        return self::$cache[$class];
    }

    public static function options(): array
    {
        $response = [];
        foreach (static::$DATA as $key => $value) {
            $response[$value] = $key;
        }
        return $response;
    }

    protected static function keysFormatter(): callable
    {
        return static fn(mixed $unused, string $key): string => strtolower($key);
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function data(): mixed
    {
        return static::$DATA[$this->value];
    }

    public function equals(Enum $other): bool
    {
        return $other === $this;
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }

    private function ensureIsBetweenAcceptedValues(mixed $value): void
    {
        if (!in_array($value, static::values(), true)) {
            $this->throwExceptionForInvalidValue($value);
        }
    }

    public static function create(mixed $value): static
    {
        return new static($value);
    }
}
