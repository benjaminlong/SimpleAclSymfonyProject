<?php

namespace SimpleAcl\Bundle\CoreBundle\Acl;

use SimpleAcl\Component\Acl\UserAclInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;
use Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use Symfony\Component\Security\Acl\Model\MutableAclInterface;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class UserAcl implements UserAclInterface
{
    private $userClass;

    /** @var MutableAclProviderInterface */
    private $aclProvider;

    public function __construct(
        $userClass,
        MutableAclProviderInterface $aclProvider
    ) {
        $this->userClass = $userClass;
        $this->aclProvider = $aclProvider;
    }

    public function installFallbackAcl()
    {
        $oid = new ObjectIdentity('class', $this->userClass);

        try {
            /** @var MutableAclInterface $acl */
            $acl = $this->aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $exist) {
            return;
        }

        $builder = new MaskBuilder();
        $builder
            ->add('view')
            ->add('edit')
            ->add('delete')
            ->add('undelete');
        $acl->insertClassAce(new RoleSecurityIdentity('ROLE_SUPER_ADMIN'), $builder->get());

        $builder->reset();
        $builder->add('view');
        $acl->insertClassAce(new RoleSecurityIdentity('ROLE_ADMIN'), $builder->get());
        $this->aclProvider->updateAcl($acl);
    }

    public function uninstallFallbackAcl()
    {
        $oid = new ObjectIdentity('class', $this->userClass);

        try {
            /** @var MutableAclInterface $acl */
            $acl = $this->aclProvider->findAcl($oid);
            $aces = $acl->getClassAces();
            foreach (array_reverse(array_keys($aces)) as $index) {
                $acl->deleteClassAce($index);
            }
        } catch (AclNotFoundException $e) {
            return;
        }

        $this->aclProvider->deleteAcl($oid);
    }
}
