<?php

namespace SimpleAcl\Component\Model;

interface UserInterface
{
    /**
     * @param UserProfileInterface $profile
     *
     * @return $this
     */
    public function setProfile(UserProfileInterface $profile = null);

    /**
     * @return UserProfileInterface
     */
    public function getProfile();
}
