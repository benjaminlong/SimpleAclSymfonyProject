<?php

namespace SimpleAcl\Bundle\ApiBundle\Document;

use FOS\OAuthServerBundle\Document\AccessToken as BaseAccessToken;
use FOS\OAuthServerBundle\Model\ClientInterface;

class AccessToken extends BaseAccessToken
{
    protected $id;
    protected $client;
    protected $user;

    public function getClient()
    {
        return $this->client;
    }

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }
}
 