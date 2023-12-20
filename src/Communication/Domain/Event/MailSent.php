<?php

namespace App\Communication\Domain\Event;

use App\Event\Domain\Bus\Event\Event;

class MailSent extends Event
{
    public function __construct(
        private readonly string $what,
        private readonly ?string $who = null,
        ?array $data = []
    ) {
        parent::__construct($data ?: []);
    }

    public function what(): string
    {
        return $this->what;
    }

    public function who(): ?string
    {
        return $this->who;
    }
}
