<?php

namespace App\Finance\Infrastructure\UI\Controller\Product;

use App\Event\Domain\Bus\Query\QueryBus;
use App\Shared\Application\Paginator;
use App\Finance\Application\Product\ListProducts\ListProductsQuery;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ProductListController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function index(Environment $twig): Response
    {
        /** @var Paginator $results */
        $results = $this->queryBus->ask(new ListProductsQuery());

        return new Response(
            $twig->render(
                'pages/finance/product/list.html.twig',
                [
                    'results' => $results
                ]
            )
        );
    }
}
