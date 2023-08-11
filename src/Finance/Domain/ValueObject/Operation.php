<?php

declare(strict_types=1);

namespace App\Finance\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Enum;
use InvalidArgumentException;

/**
 * @method static Operation purchase()
 * @method static Operation sale()
 */
class Operation extends Enum
{
    final public const PURCHASE = 0;
    final public const SALE = 1;

    protected static array $DATA = [
        self::PURCHASE => 'Purchase',
        self::SALE => 'Sale',
    ];

    protected function throwExceptionForInvalidValue($value)
    {
        throw new InvalidArgumentException(sprintf('The operation <%s> is invalid', $value));
    }
}
