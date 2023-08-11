<?php

declare(strict_types=1);

namespace App\Warehouse\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Enum;
use InvalidArgumentException;

/**
 * @method static StatusRequest pending()
 * @method static StatusRequest sent()
 */
class StatusRequest extends Enum
{
    final public const PENDING = 0;
    final public const SENT = 1;

    protected static array $DATA = [
        self::PENDING => 'Pending',
        self::SENT => 'Sent',
    ];

    protected function throwExceptionForInvalidValue($value)
    {
        throw new InvalidArgumentException(sprintf('The Status Request <%s> is invalid', $value));
    }
}
