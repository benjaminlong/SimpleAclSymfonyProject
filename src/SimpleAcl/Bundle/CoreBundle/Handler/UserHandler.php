<?php

namespace SimpleAcl\Bundle\CoreBundle\Handler;

use Doctrine\ODM\MongoDB\DocumentManager;
use SimpleAcl\Component\Handler\UserHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

class UserHandler implements UserHandlerInterface
{
    protected $dm;

    protected $repository;

    protected $formType;

    protected $formFactory;

    public function __construct(
        DocumentManager $dm,
        FormFactoryInterface $formFactory,
        FormTypeInterface $formType,
        $entityClass
    ) {
        $this->dm = $dm;
        $this->formFactory = $formFactory;
        $this->formType = $formType;
        $this->repository = $this->dm->getRepository($entityClass);
    }

    public function get($id)
    {
        return $this->repository->find($id);
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }
}
 