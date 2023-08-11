<?php

namespace App\Store\Domain\Exception;

use App\Store\Domain\Entity\Product;

class NotEnoughItemProductsException extends \DomainException
{
    public function __construct(protected readonly Product $product)
    {
        parent::__construct(sprintf('The product <%s> has not been enough items', $this->product->getCode()));
    }
}
