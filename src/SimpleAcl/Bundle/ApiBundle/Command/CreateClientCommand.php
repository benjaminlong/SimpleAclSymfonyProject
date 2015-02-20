<?php

namespace SimpleAcl\Bundle\ApiBundle\Command;

use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('oauth2:client:create')
            ->setDescription('Create a client.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $router = $this->getContainer()->get('router');

        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        /** @var ClientInterface $client */
        $client = $clientManager->createClient();
        $client->setRedirectUris(array('http://localhost/SimpleAclProject/web/app_dev.php'));
        $client->setAllowedGrantTypes(array(
            'token',
            'authorization_code',
            'password',
            'client_credentials'
        ));
        $clientManager->updateClient($client);

        $output->writeln($router->generate('fos_oauth_server_authorize', array(
            'client_id' => $client->getPublicId(),
            'redirect_uri' => 'http://localhost/SimpleAclProject/web/app_dev.php',
            'response_type' => 'code'
        )));
    }
}
