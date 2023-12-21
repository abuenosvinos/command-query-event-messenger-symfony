<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter;
use App\Shared\Domain\Criteria\FilterField;
use App\Shared\Domain\Criteria\FilterOperator;
use App\Shared\Domain\Criteria\OrderBy;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\QueryBuilder;

final class DoctrineCriteriaConverter
{
    /**
     * @param array<string> $criteriaToDoctrineFields
     * @param array<mixed> $hydrators
     */
    public function __construct(
        private readonly Criteria $criteria,
        private readonly array $criteriaToDoctrineFields = [],
        private readonly array $hydrators = []
    ) {
    }

    /**
     * @param array<string> $criteriaToDoctrineFields
     * @param array<mixed> $hydrators
     */
    public static function applyFilters(
        QueryBuilder $queryBuilder,
        Criteria $criteria,
        array $criteriaToDoctrineFields = [],
        array $hydrators = [],
    ): void {

        $converter = new self($criteria, $criteriaToDoctrineFields, $hydrators);

        $queryBuilder->addCriteria(
            new DoctrineCriteria(
                null,
                $converter->formatOrder(),
                $criteria->offset(),
                $criteria->limit()
            )
        );

        if ($criteria->hasFilters()) {
            $expressionsComparison = [];

            $plainFilters = $criteria->plainFilters();
            foreach ($plainFilters as $filter) {
                $field = $converter->mapFieldValue($filter->field());
                $value = $converter->existsHydratorFor($field)
                    ? $converter->hydrate($field, $filter->value()->value())
                    : $filter->value()->value();
                if (in_array($filter->operator()->value(), [FilterOperator::INSTANCE_OF])) {
                    $expression = new Value(sprintf('%s %s %s', $field, $filter->operator()->value(), $value));
                    $queryBuilder->addCriteria(new DoctrineCriteria($expression));
                } else {
                    $expressionsComparison[] = new Comparison($field, $filter->operator()->value(), $value);
                }
            }

            if (count($expressionsComparison) > 0) {
                $queryBuilder->addCriteria(new DoctrineCriteria(
                    new CompositeExpression(
                        CompositeExpression::TYPE_AND,
                        $expressionsComparison
                    )
                ));
            }
        }
    }

    /**
     * @param array<string> $criteriaToDoctrineFields
     * @param array<mixed> $hydrators
     */
    public static function convert(
        Criteria $criteria,
        array $criteriaToDoctrineFields = [],
        array $hydrators = []
    ): DoctrineCriteria {
        $converter = new self($criteria, $criteriaToDoctrineFields, $hydrators);

        return $converter->convertToDoctrineCriteria();
    }

    private function convertToDoctrineCriteria(): DoctrineCriteria
    {
        return new DoctrineCriteria(
            $this->buildExpression(),
            $this->formatOrder(),
            $this->criteria->offset(),
            $this->criteria->limit()
        );
    }

    private function buildExpression(): ?CompositeExpression
    {
        if ($this->criteria->hasFilters()) {
            return new CompositeExpression(
                CompositeExpression::TYPE_AND,
                array_map($this->buildComparison(), $this->criteria->plainFilters())
            );
        }

        return null;
    }

    private function buildComparison(): callable
    {
        return function (Filter $filter): Comparison {
            $field = $this->mapFieldValue($filter->field());
            $value = $this->existsHydratorFor($field)
                ? $this->hydrate($field, $filter->value()->value())
                : $filter->value()->value();

            return new Comparison($field, $filter->operator()->value(), $value);
        };
    }

    private function mapFieldValue(FilterField $field): string
    {
        return array_key_exists($field->value(), $this->criteriaToDoctrineFields)
            ? $this->criteriaToDoctrineFields[$field->value()]
            : $field->value();
    }

    /**
     * @return array<string, string>|null
     */
    private function formatOrder(): ?array
    {
        if (!$this->criteria->hasOrder()) {
            return null;
        }

        return [
            $this->mapOrderBy($this->criteria->order()->orderBy()) => $this->criteria->order()->orderType()->value()
        ];
    }

    private function mapOrderBy(OrderBy $field): string
    {
        return array_key_exists($field->value(), $this->criteriaToDoctrineFields)
            ? $this->criteriaToDoctrineFields[$field->value()]
            : $field->value();
    }

    private function existsHydratorFor(mixed $field): bool
    {
        return array_key_exists($field, $this->hydrators);
    }

    private function hydrate(mixed $field, mixed $value): mixed
    {
        return $this->hydrators[$field]($value);
    }
}
