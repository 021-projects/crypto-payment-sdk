<?php

namespace O21\CryptoPaymentApi\Methods;

use O21\CryptoPaymentApi\Objects\UserPatch;
use O21\CryptoPaymentApi\Objects\UserServer;

trait Users
{
    public function createUserForServer(
        string $callback_url,
        bool $setTokenInClient = true
    ): UserServer {
        $user = new UserServer(
            $this->post('users/server', compact('callback_url'))
        );
        
        if ($setTokenInClient) {
            $this->setApiToken($user->api_token);
        }

        return $user;
    }

    public function patchUser(
        ?string $callback_url = null
    ): UserPatch {
        return new UserPatch(
            $this->patch('users', compact('callback_url'))
        );
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