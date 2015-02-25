<?php

namespace SimpleAcl\Bundle\CoreBundle\Handler;

use Doctrine\ODM\MongoDB\DocumentManager;
use SimpleAcl\Bundle\CoreBundle\Document\User;
use SimpleAcl\Component\Handler\UserHandlerInterface;
use SimpleAcl\Component\Model\UserInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;

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

    public function patch(UserInterface $user, array $parameters)
    {
        return $this->processForm($user, $parameters, 'PATCH');
    }

    public function post(array $parameters)
    {
        return $this->processForm($this->createNew(), $parameters, 'POST');
    }

    private function createNew()
    {
        return new User();
    }

    private function processForm(
        UserInterface $user,
        array $parameters,
        $method = "PUT"
    ) {
        $form = $this->formFactory->create(
            $this->formType,
            $user,
            array('method' => $method)
        );

        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $user = $form->getData();
            $this->dm->persist($user);
            $this->dm->flush();

            return $user;
        }

        throw new InvalidArgumentException('Invalid submitted data', $form);
    }
}
 