<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

final class Criteria
{
    public function __construct(
        private ?Filters $filters,
        private ?Order $order,
        private ?int $offset = null,
        private ?int $limit = null
    ) {
    }

    public static function fromFilters(array $filters): self
    {
        return new self(
            new Filters($filters),
            null,
            0,
            1000000
        );
    }

    public static function fromScratch(): self
    {
        return new self(
            new Filters([]),
            null,
            0,
            1000000
        );
    }

    public function plainFilters(): array
    {
        return isset($this->filters) ? $this->filters->filters() : [];
    }

    public function filters(): ?Filters
    {
        return $this->filters;
    }

    public function hasFilters(): bool
    {
        return isset($this->filters) && $this->filters->count() > 0;
    }

    public function addFilter(Filter $filter): Criteria
    {
        return new Criteria(
            $this->filters->add($filter),
            $this->order,
            $this->offset,
            $this->limit
        );
    }

    public function order(): ?Order
    {
        return $this->order;
    }

    public function hasOrder(): bool
    {
        return isset($this->order) && !$this->order->isNone();
    }

    public function setOrder(Order $order): Criteria
    {
        return new Criteria(
            $this->filters,
            $order,
            $this->offset,
            $this->limit
        );
    }

    public function offset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): Criteria
    {
        return new Criteria(
            $this->filters,
            $this->order,
            $offset,
            $this->limit
        );
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): Criteria
    {
        return new Criteria(
            $this->filters,
            $this->order,
            $this->offset,
            $limit
        );
    }

    public function serialize(): string
    {
        return sprintf(
            '%s~~%s~~%s~~%s',
            $this->filters?->serialize(),
            $this->order?->serialize(),
            $this->offset,
            $this->limit
        );
    }
}
