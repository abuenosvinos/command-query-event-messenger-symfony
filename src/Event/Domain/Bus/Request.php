<?php

declare(strict_types=1);

namespace App\Event\Domain\Bus;

abstract class Request
{
    private Uuid $requestId;

    public function __construct(Uuid $requestId = null)
    {
        $this->requestId = $requestId ?: Uuid::random();
    }

    public function requestId(): Uuid
    {
        return $this->requestId;
    }

    abstract public function requestType(): string;
}
