<?php

namespace SimpleAcl\Component\Handler;

use SimpleAcl\Component\Model\UserInterface;

interface UserHandlerInterface
{
    public function get($id);

    public function getAll();

    public function post(array $parameters);

    public function patch(UserInterface $user, array $parameters);
}
