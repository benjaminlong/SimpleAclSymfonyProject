<?php

namespace SimpleAcl\Component\Acl;

interface UserAclInterface
{
    /**
     * Install the default 'fallback Acl entries for generic access
     *
     * @return void
     */
    public function installFallbackAcl();

    /**
     * Uninstall default Acl entries
     *
     * @return void
     */
    public function uninstallFallbackAcl();
}
