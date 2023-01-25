<?php

namespace Tests;

use Dotenv\Dotenv;
use O21\CryptoPaymentApi\Client;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpEnvironment();
        $this->assertEnvironmentFilled();

        $this->setUpClient();
    }

    protected function setUpEnvironment(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();
    }

    protected function assertEnvironmentFilled(): void
    {
        if (! env('PUBLIC_KEY')) {
            $this->markTestSkipped('Public key is missed. Please indicate it in .env file.');
        }
        if (! env('PRIVATE_KEY')) {
            $this->markTestSkipped('Private key is missed. Please indicate it in .env file.');
        }
        if (! env('WALLET_TYPE')) {
            $this->markTestSkipped('Wallet type is missed. Please indicate it in .env file.');
        }
    }

    protected function setUpClient(): void
    {
        $this->client = new Client(env('PUBLIC_KEY'), env('PRIVATE_KEY'), env('API_URL'));
        $this->client->setWalletType(env('WALLET_TYPE'));
    }

    protected function assertArrayStructure(array $keys, array $haystack): void
    {
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $haystack);
        }
    }
}