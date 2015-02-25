<?php

namespace SimpleAcl\Component\Handler;

use SimpleAcl\Component\Model\ProductInterface;

interface ProductHandlerInterface
{
    public function get($id);

    public function getAll();

    public function post(array $parameters);

    public function patch(ProductInterface $product, array $parameters);
}
