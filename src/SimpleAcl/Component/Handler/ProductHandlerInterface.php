<?php

namespace SimpleAcl\Component\Handler;

interface ProductHandlerInterface
{
    public function get($id);

    public function getAll();

    public function post(array $parameters);
}
