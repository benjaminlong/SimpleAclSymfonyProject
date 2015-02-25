<?php

namespace SimpleAcl\Component\Model;

interface UserProfileInterface
{
    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday);

    /**
     * @return mixed
     */
    public function getBirthday();

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName);

    /**
     * @return mixed
     */
    public function getFirstName();

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName);

    /**
     * @return mixed
     */
    public function getLastName();

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname);

    /**
     * @return mixed
     */
    public function getNickname();

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber);

    /**
     * @return mixed
     */
    public function getPhoneNumber();

    /**
     * @return mixed
     */
    public function getUsers();
}
