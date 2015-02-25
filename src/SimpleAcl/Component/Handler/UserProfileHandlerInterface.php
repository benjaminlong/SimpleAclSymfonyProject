<?php

namespace SimpleAcl\Component\Handler;

use SimpleAcl\Component\Model\UserProfileInterface;

interface UserProfileHandlerInterface
{
    public function get($id);

    public function post(array $parameters);

    public function patch(UserProfileInterface $profile, array $parameters);
}
