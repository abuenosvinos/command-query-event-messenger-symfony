<?php

namespace App\Data\Infrastructure\UI\Controller\LogBook;

use App\Data\Application\ListLogBook\ListLogBookQuery;
use App\Event\Domain\Bus\Query\QueryBus;
use App\Shared\Application\Paginator;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LogBookListController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function index(Environment $twig): Response
    {
        /** @var Paginator $results */
        $results = $this->queryBus->ask(new ListLogBookQuery());

        return new Response(
            $twig->render(
                'pages/data/logbook/list.html.twig',
                [
                    'results' => $results
                ]
            )
        );
    }
}
