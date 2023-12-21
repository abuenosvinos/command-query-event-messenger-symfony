<?php

namespace App\Finance\Infrastructure\UI\Controller\Product;

use App\Event\Domain\Bus\Query\QueryBus;
use App\Finance\Domain\Entity\Product;
use App\Shared\Application\Paginator;
use App\Finance\Application\Product\ListProducts\ListProductsQuery;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ProductListController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function index(Environment $twig): Response
    {
        /** @var Paginator<Product> $results */
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
