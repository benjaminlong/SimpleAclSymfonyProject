<?php

namespace SimpleAcl\Bundle\CoreBundle\Handler;

use Doctrine\ODM\MongoDB\DocumentManager;
use SimpleAcl\Bundle\CoreBundle\Document\UserProfile;
use SimpleAcl\Component\Handler\UserProfileHandlerInterface;
use SimpleAcl\Component\Model\UserProfileInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;

class UserProfileHandler implements UserProfileHandlerInterface
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

    public function patch(UserProfileInterface $profile, array $parameters)
    {
        return $this->processForm($profile, $parameters, 'PATCH');
    }

    public function post(array $parameters, $flush = true)
    {
        return $this->processForm($this->createNew(), $parameters, 'POST');
    }

    private function createNew()
    {
        return new UserProfile();
    }

    private function processForm(
        UserProfileInterface $profile,
        array $parameters,
        $method = "PUT"
    ) {
        $form = $this->formFactory->create(
            $this->formType,
            $profile,
            array('method' => $method)
        );

        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $profile = $form->getData();
            $this->dm->persist($profile);
            $this->dm->flush();

            return $profile;
        }

        throw new InvalidArgumentException('Invalid submitted data', $form);
    }
}
