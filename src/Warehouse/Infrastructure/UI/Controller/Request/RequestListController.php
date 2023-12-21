<?php

namespace App\Warehouse\Infrastructure\UI\Controller\Request;

use App\Event\Domain\Bus\Query\QueryBus;
use App\Shared\Application\Paginator;
use App\Warehouse\Application\Request\ListRequest\ListRequestQuery;
use App\Warehouse\Domain\Entity\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class RequestListController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function index(Environment $twig): Response
    {
        /** @var Paginator<Request> $results */
        $results = $this->queryBus->ask(new ListRequestQuery());

        return new Response(
            $twig->render(
                'pages/warehouse/request/list.html.twig',
                [
                    'results' => $results
                ]
            )
        );
    }
}
