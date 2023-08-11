<?php

namespace App\Finance\Infrastructure\UI\Controller\Request;

use App\Event\Domain\Bus\Query\QueryBus;
use App\Shared\Application\Paginator;
use App\Finance\Application\Request\ListRequest\ListRequestQuery;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class RequestListController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function index(Environment $twig): Response
    {
        /** @var Paginator $results */
        $results = $this->queryBus->ask(new ListRequestQuery());

        return new Response(
            $twig->render(
                'pages/finance/request/list.html.twig',
                [
                    'results' => $results
                ]
            )
        );
    }
}
