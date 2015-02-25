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

    public function post(array $parameters, $flush = true)
    {
        $profile = $this->createProduct(); // factory method create an empty product

        // Process form does all the magic, validate and hydrate the product Object.
        return $this->processForm($profile, $parameters, 'POST', $flush);
    }

    private function createProduct()
    {
        return new UserProfile();
    }

    private function processForm(
        UserProfileInterface $profile,
        array $parameters,
        $method = "PUT",
        $flush = true
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
            if ($flush) {
                $this->dm->flush();
            }

            return $profile;
        }

        throw new InvalidArgumentException('Invalid submitted data', $form);
    }
}
