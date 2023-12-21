<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus\Event;

class EventOptions
{
    /**
     * @param array<string, mixed> $options
     */
    private function __construct(private array $options)
    {
    }

    public function set(string $key, mixed $value): self
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function get(string $key): mixed
    {
        return $this->options[$key] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->options;
    }

    /**
     * @param array<string, mixed> $options
     */
    public static function fromData(array $options = []): self
    {
        return new self($options);
    }
}
