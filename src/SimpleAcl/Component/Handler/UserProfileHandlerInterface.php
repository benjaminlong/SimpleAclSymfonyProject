<?php

namespace SimpleAcl\Component\Handler;

interface UserProfileHandlerInterface
{
    public function get($id);

    public function post(array $parameters);
}
