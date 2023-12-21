<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @template TKey
 * @template TValue
 * @template-implements IteratorAggregate<mixed>
 */
abstract class Collection implements Countable, IteratorAggregate
{
    /**
     * @param array<TKey, TValue> $items
     */
    public function __construct(private readonly array $items)
    {
        Assert::arrayOf($this->type(), $items);
    }

    abstract protected function type(): string;

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items());
    }

    final public function count(): int
    {
        return \count($this->items());
    }

    /**
     * @return array<TKey, TValue>
     */
    protected function items(): array
    {
        return $this->items;
    }
}
