<?php

declare(strict_types=1);

namespace App\Shared\Domain\Trait;

trait Sluggable
{
    private string $slug;

    public function slug(): string
    {
        return $this->slug;
    }
}
