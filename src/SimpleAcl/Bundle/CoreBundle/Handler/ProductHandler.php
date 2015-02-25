<?php

namespace SimpleAcl\Bundle\CoreBundle\Handler;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Doctrine\ODM\MongoDB\DocumentManager;
use SimpleAcl\Bundle\CoreBundle\Document\Product;
use SimpleAcl\Component\Handler\ProductHandlerInterface;
use SimpleAcl\Component\Model\ProductInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

class ProductHandler implements ProductHandlerInterface
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

    public function patch(ProductInterface $product, array $parameters)
    {
        return $this->processForm($product, $parameters, 'PATCH');
    }

    public function post(array $parameters)
    {
        return $this->processForm($this->createNew(), $parameters, 'POST');
    }

    private function createNew()
    {
        return new Product();
    }

    private function processForm(ProductInterface $product, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(
            $this->formType,
            $product,
            array('method' => $method)
        );

        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $product = $form->getData();
            $this->dm->persist($product);
            $this->dm->flush($product);

            return $product;
        }

        throw new InvalidArgumentException('Invalid submitted data', $form);
    }
}
 