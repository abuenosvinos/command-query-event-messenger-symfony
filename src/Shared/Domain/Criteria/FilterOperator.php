<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\ValueObject\Enum;
use InvalidArgumentException;

/**
 * @method static FilterOperator gt()
 * @method static FilterOperator lt()
 * @method static FilterOperator contains()
 * @method static FilterOperator equal()
 * @method static FilterOperator not_equal()
 * @method static FilterOperator in()
 */
final class FilterOperator extends Enum
{
    final public const EQUAL = '=';
    final public const NOT_EQUAL = '<>';
    final public const GT = '>';
    final public const GTE = '>=';
    final public const LT = '<';
    final public const LTE = '<=';
    final public const CONTAINS = 'CONTAINS';
    final public const NOT_CONTAINS = 'NOT_CONTAINS';
    final public const STARTS_WITH = 'STARTS_WITH';
    final public const ENDS_WITH   = 'ENDS_WITH';
    final public const IN = 'IN';
    final public const NOT_IN = 'NIN';
    final public const INSTANCE_OF = 'INSTANCE OF';

    private static array $containing = [self::CONTAINS, self::NOT_CONTAINS];

    public function isContaining(): bool
    {
        return in_array($this->value(), self::$containing, true);
    }

    protected function throwExceptionForInvalidValue($value): void
    {
        throw new InvalidArgumentException(sprintf('The filter <%s> is invalid', $value));
    }
}
