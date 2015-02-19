<?php

namespace SimpleAcl\Bundle\CoreBundle\Handler;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use SimpleAcl\Bundle\CoreBundle\Document\Product;
use SimpleAcl\Bundle\CoreBundle\Form\Type\ProductType;
use SimpleAcl\Component\Handler\ProductHandlerInterface;
use SimpleAcl\Component\Model\ProductInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ProductHandler implements ProductHandlerInterface
{
    protected $om;

    protected $entityClass;

    protected $repository;

    private $formFactory;

    public function __construct(
        ManagerRegistry $om,
        FormFactoryInterface $formFactory,
        $entityClass
    ) {
        $this->om = $om;
        $this->formFactory = $formFactory;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
    }

    public function get($id)
    {
        return $this->repository->find($id);
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
            new ProductType($this->entityClass),
            $product,
            array('method' => $method)
        );

        var_dump($parameters);

        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $product = $form->getData();
            $this->om->persist($product);
            $this->om->flush($product);

            return $product;
        }

        throw new InvalidArgumentException('Invalid submitted data', $form);
    }
}
 