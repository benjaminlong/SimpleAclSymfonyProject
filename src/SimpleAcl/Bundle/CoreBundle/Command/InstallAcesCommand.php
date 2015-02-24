<?php

namespace SimpleAcl\Bundle\CoreBundle\Command;

use SimpleAcl\Component\Acl\UserAclInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallAcesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:acl:installAces')
            ->setDescription('Install global ACEs')
            ->setDefinition(array(
                new InputOption('flush', null, InputOption::VALUE_NONE, 'Flush existing Acls'),
            ))
            ->setHelp(
                <<<EOT
This command should be run once during the installation process or after enabling Acl for the first time.

If you have been using application previously without Acl and are just enabling it, you will also
need to run app:acl:fixAces.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $this->getContainer()->has('security.acl.provider')) {
            $output->writeln('You must setup the ACL system, see the symfony2 documentation for how to do this.');

            return;
        }

        /** @var UserAclInterface $userAcl */
        $userAcl = $this->getContainer()->get('simple_acl.acl.user');
        if ($input->getOption('flush')) {
            $output->writeln('Flushing Global ACEs');

            $userAcl->uninstallFallbackAcl();
            $output->writeln('Done.');
        }

        $output->writeln('Installing Global ACEs ...');
        $userAcl->installFallbackAcl();

        $output->writeln('Global ACEs have been installed.');
    }
}
