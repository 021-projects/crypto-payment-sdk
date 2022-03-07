<?php

namespace O21\CryptoPaymentApi\Methods;

use O21\CryptoPaymentApi\Objects\UserServer;

trait Users
{
    public function createUserForServer(bool $setTokenInClient = true): UserServer
    {
        $user = new UserServer(
            $this->post('users/server')
        );
        
        if ($setTokenInClient) {
            $this->setApiToken($user->api_token);
        }

        return $user;
    }

    public function regenerateKeysForUser(bool $setTokenInClient = true): UserServer
    {
        $user = new UserServer(
            $this->post('users/regenerate-keys')
        );

        if ($setTokenInClient) {
            $this->setApiToken($user->api_token);
        }

        return $user;
    }
}