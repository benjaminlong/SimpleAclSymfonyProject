<?php

namespace SimpleAcl\Bundle\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends FOSRestController
{
    public function getProductAction($id)
    {
        $page = $this->container
            ->get('simple_acl.product.handler')
            ->get($id);

        return $page;
    }

    public function postProductAction(Request $request)
    {
        try {
            // Hey Page handler create a new Page.
            $newProduct = $this->container->get('simple_acl.product.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newProduct->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('simple_acl_get_product', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidArgumentException $exception) {
            return $exception;
        }
    }

    /**
     * @return \FOS\RestBundle\View\ViewHandler
     */
    private function getViewHandler()
    {
        return $this->container->get('fos_rest.view_handler');
    }
}
