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

    public function post(array $parameters)
    {
        $product = $this->createProduct(); // factory method create an empty product

        // Process form does all the magic, validate and hydrate the product Object.
        return $this->processForm($product, $parameters, 'POST');
    }

    private function createProduct()
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
 