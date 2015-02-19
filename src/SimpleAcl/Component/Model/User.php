<?php

namespace SimpleAcl\Component\Model;

use FOS\UserBundle\Document\User as BaseUser;

class User extends BaseUser implements UserInterface
{
    protected $id;

    /** @var  UserProfileInterface $profile */
    protected $profile;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function setProfile(UserProfileInterface $profile = null)
    {
        if ($this->profile) {
            $this->profile->getUsers()->removeElement($this);
        }
        $this->profile = $profile;
        if ($profile) {
            $profile->getUsers()->add($this);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
