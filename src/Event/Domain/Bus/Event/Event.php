<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus\Event;

use App\Event\Domain\Bus\Request;
use App\Event\Domain\Bus\Uuid;
use DateTimeImmutable;
use RuntimeException;

abstract class Event extends Request
{
    private array $data;
    private string $occurredOn;

    public function __construct(
        array $data = [],
        Uuid $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($eventId);

        $this->data        = $data;
        $this->occurredOn  = $occurredOn ?: (new DateTimeImmutable())->format('Y-m-d\TH:i:s.000\Z');
    }

    public function requestType(): string
    {
        return 'event';
    }

    public function data(): array
    {
        return $this->data;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    public function __call(string $method, array $args)
    {
        $attributeName = $method;
        if (str_starts_with($method, 'is')) {
            $attributeName = lcfirst(substr($method, 2));
        }

        if (str_starts_with($method, 'has')) {
            $attributeName = lcfirst(substr($method, 3));
        }

        if (isset($this->data[$attributeName])) {
            return $this->data[$attributeName];
        }

        throw new RuntimeException(sprintf('The method "%s" does not exist.', $method));
    }
}
