<?php

declare(strict_types=1);

namespace  App\Event\Domain\Bus\Query;

final class QueryNotRegisteredError extends \DomainException
{
    public function __construct(private readonly Query $query)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'query_bus_not_registered_error';
    }

    protected function errorMessage(): string
    {
        return sprintf('The query <%s> has not been registered', get_class($this->query));
    }
}
