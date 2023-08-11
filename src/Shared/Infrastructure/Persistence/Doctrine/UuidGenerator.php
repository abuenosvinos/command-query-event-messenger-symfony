<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;

class UuidGenerator extends AbstractIdGenerator
{
    public function generate(EntityManagerInterface $em, $entity): Uuid
    {
        return Uuid::random();
    }

    public function generateId(EntityManagerInterface $em, $entity): Uuid
    {
        return Uuid::random();
    }
}
