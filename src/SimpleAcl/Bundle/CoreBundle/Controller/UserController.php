<?php

namespace SimpleAcl\Bundle\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends FOSRestController
{
    public function getUserAction($id)
    {
        $formHandler = $this->container->get('simple_acl.user.handler');
        $resource = $formHandler->get($id);

        if (is_null($resource)) {
            throw new BadRequestHttpException();
        }

        $view = $this
            ->view()
            ->setData($resource);

        return $this->handleView($view);
    }

    public function getUsersAction()
    {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $formHandler = $this->container->get('simple_acl.user.handler');
        $resources = $formHandler->getAll();

        $view = $this
            ->view()
            ->setData($resources);

        return $this->handleView($view);
    }

    public function meUserAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $view = $this->view($user);

        return $this->handleView($view);
    }
}
 