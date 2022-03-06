<?php

namespace Tests;

use Illuminate\Support\Collection;
use O21\CryptoPaymentApi\Objects\Wallet;

class WalletsTest extends TestCase
{
    public function test_get_wallet_index(): void
    {
        $result = $this->client->getWalletIndex();

        $this->assertIsArray($result);
        $this->assertArrayStructure(['wallet', 'fees', 'rates'], $result);

        $this->assertInstanceOf(Wallet::class, $result['wallet']);
        $this->assertInstanceOf(Collection::class, $result['fees']);
        $this->assertIsArray($result['rates']);
    }

    public function test_get_available_list(): void
    {
        $this->assertIsArray($this->client->getAvailableList());
    }

    public function test_get_transactions(): void
    {
        $this->client->getTransactions();
        $this->addToAssertionCount(1);
    }
}