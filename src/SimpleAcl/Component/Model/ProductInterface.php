<?php

namespace SimpleAcl\Component\Model;


interface ProductInterface
{
    /**
     * @param mixed $description
     */
    public function setDescription($description);

    /**
     * @return mixed
     */
    public function getDescription();

    /**
     * @param mixed $name
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param mixed $price
     */
    public function setPrice($price);

    /**
     * @return mixed
     */
    public function getPrice();
}
