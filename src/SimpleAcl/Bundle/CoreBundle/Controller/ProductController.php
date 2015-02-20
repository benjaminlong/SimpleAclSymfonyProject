<?php

namespace SimpleAcl\Bundle\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProductController extends FOSRestController
{
    public function getProductsAction()
    {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $formHandler = $this->container->get('simple_acl.product.handler');
        $resources = $formHandler->getAll();

        $view = $this
            ->view()
            ->setData($resources);

        return $this->handleView($view);
    }

    public function getProductAction($id)
    {
        $formHandler = $this->container->get('simple_acl.product.handler');
        $resource = $formHandler->get($id);

        if (is_null($resource)) {
            throw new BadRequestHttpException();
        }

        $view = $this
            ->view()
            ->setData($resource);

        return $this->handleView($view);
    }

    public function postProductAction(Request $request)
    {
        # this is it
        var_dump("HERE");
//        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
//            throw new AccessDeniedException();
//        }

        try {
            $formHandler = $this->container->get('simple_acl.product.handler');
            $resource = $formHandler->post($request->request->all());

            $view = $this
                ->view()
                ->setData($resource);

            return $this->handleView($view);
        } catch (InvalidArgumentException $exception) {
            return $exception;
        }
    }
}
