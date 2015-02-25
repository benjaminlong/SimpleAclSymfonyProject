<?php

namespace SimpleAcl\Bundle\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use SimpleAcl\Component\Model\UserInterface;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class UserProfileController extends FOSRestController
{
    public function postProfileAction(Request $request, $id)
    {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        /** @var UserInterface $user */
        $user = $this->get('simple_acl.user.handler')->get($id);

        $authorizationChecker = $this->get('security.authorization_checker');
        if (false === $authorizationChecker->isGranted('EDIT', $user)) {
            throw new AccessDeniedException();
        }

        if (!is_null($user->getProfile())) {
            throw new ConflictHttpException('Profile already exist');
        }

        try {
            $formHandler = $this->container->get('simple_acl.user_profile.handler');
            $resource = $formHandler->post($request->request->all());
            $this->get('simple_acl.user.handler')->patch($user, array('profile' => $resource->getId()));

            // creating the ACL
            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($resource);
            $acl = $aclProvider->createAcl($objectIdentity);

            //
            $securityIdentity = UserSecurityIdentity::fromAccount($user);

            // grant owner access
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);

            $view = $this
                ->view()
                ->setData($resource);

            return $this->handleView($view);
        } catch (InvalidArgumentException $exception) {
            return $exception;
        }
    }
}
 