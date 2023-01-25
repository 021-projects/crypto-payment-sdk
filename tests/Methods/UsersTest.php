<?php

namespace Tests\Methods;

use Tests\TestCase;

class UsersTest extends TestCase
{
    public function testMe(): void
    {
        $response = $this->client->me();
        $this->assertIsArray($response);
        $this->assertNotNull($response['visitor']);
        $this->assertIsNumeric($response['visitor']->id);
        $this->assertIsString($response['visitor']->name);
        $this->assertIsString($response['visitor']->state);
        $this->assertIsArray($response['wallets']);
        $this->assertNotEmpty($response['wallets']);
    }
}
