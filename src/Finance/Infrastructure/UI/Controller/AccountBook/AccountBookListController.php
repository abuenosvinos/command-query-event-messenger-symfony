<?php

namespace App\Finance\Infrastructure\UI\Controller\AccountBook;

use App\Event\Domain\Bus\Query\QueryBus;
use App\Finance\Application\AccountBook\ListAccountsBook\ListAccountsBookQuery;
use App\Finance\Domain\Entity\AccountBook;
use App\Shared\Application\Paginator;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AccountBookListController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function index(Environment $twig): Response
    {
        /** @var Paginator<AccountBook> $results */
        $results = $this->queryBus->ask(new ListAccountsBookQuery());

        return new Response(
            $twig->render(
                'pages/finance/accountbook/list.html.twig',
                [
                    'results' => $results
                ]
            )
        );
    }
}
