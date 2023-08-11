<?php

declare(strict_types=1);

namespace App\Shared\Application;

use ArrayIterator;

class Paginator
{
    private ArrayIterator $results;
    private int $total;
    private int $offset;
    private int $limit;

    public function __construct(ArrayIterator $results, int $total, ?int $offset, ?int $limit)
    {
        $this->results = $results;
        $this->total = $total;
        $this->offset = ($offset) ? $offset : 0;
        $this->limit = ($limit) ? $limit : $total;
    }

    public function results(): ArrayIterator
    {
        return $this->results;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function page(): int
    {
        return 1 + intdiv($this->offset, $this->limit);
    }

    public function numPages(): int
    {
        $numPages = intdiv($this->total, $this->limit);
        if (($this->total % $this->limit) > 0) {
            $numPages++;
        }
        return $numPages;
    }

    public function firstShowed(): int
    {
        return max($this->offset, 1);
    }

    public function lastShowed(): int
    {
        return min($this->offset + $this->limit, $this->total);
    }
}
