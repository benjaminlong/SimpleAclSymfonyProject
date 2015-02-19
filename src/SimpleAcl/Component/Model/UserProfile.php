<?php

namespace SimpleAcl\Component\Model;

use Doctrine\Common\Collections\ArrayCollection;

class UserProfile implements UserProfileInterface
{
    protected $id;

    protected $nickName;

    protected $firstName;

    protected $lastName;

    protected $birthday;

    protected $phoneNumber;

    protected $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @inheritdoc
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @inheritdoc
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @inheritdoc
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @inheritdoc
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @inheritdoc
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @inheritdoc
     */
    public function setNickName($nickName)
    {
        $this->nickName = $nickName;
    }

    /**
     * @inheritdoc
     */
    public function getNickName()
    {
        return $this->nickName;
    }

    /**
     * @inheritdoc
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @inheritdoc
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @inheritdoc
     */
    public function getUsers()
    {
        return $this->users;
    }
}
