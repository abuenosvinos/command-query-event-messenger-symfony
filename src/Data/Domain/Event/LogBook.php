<?php

namespace App\Data\Domain\Event;

use App\Event\Domain\Bus\Event\Event;

class LogBook extends Event
{
    public function __construct(
        private readonly string $action,
        private ?string $email,
        private readonly ?string $objectType,
        private readonly ?string $objectId,
        ?array $data = []
    ) {
        parent::__construct($data);
    }

    public function action(): ?string
    {
        return $this->action;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function objectType(): ?string
    {
        return $this->objectType;
    }

    public function objectId(): ?string
    {
        return $this->objectId;
    }
}
