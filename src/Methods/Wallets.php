<?php

namespace O21\CryptoPaymentApi\Methods;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use O21\CryptoPaymentApi\Exceptions\WalletException;
use O21\CryptoPaymentApi\Objects\Fee;
use O21\CryptoPaymentApi\Objects\Wallet as WalletObject;

trait Wallets
{
    protected string $walletType = '';

    #[ArrayShape(['wallet' => WalletObject::class, 'fees' => Collection::class, 'rates' => 'array'])]
    public function getWalletIndex(?string $type = null): array
    {
        $response = $this->get($this->walletsEndpoint($type));

        $wallet = new WalletObject($response['wallet']);
        $fees = collect($response['fees'])
            ->map(fn(array $fee) => new Fee($fee));
        $rates = $response['rates'];

        return compact('wallet', 'fees', 'rates');
    }

    public function getAvailableList(): array
    {
        return $this->get('wallets/available-list');
    }

    public function getTransactions(
        int $page = 1,
        ?string $type = null
    ): LengthAwarePaginator {
        $response = $this->get(
            $this->walletsEndpoint($type, '/transactions'),
            compact('page')
        );

        return new LengthAwarePaginator(
            $response['items'],
            $response['total'],
            $response['perPage'],
            $response['currentPage']
        );
    }

    /**
     * @param  string  $address
     * @param  string| float $amount
     * @param  string|float $feePerKbyte
     * @param  string|null  $type
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \O21\CryptoPaymentApi\Exceptions\ValidationException
     */
    public function withdraw(
        string $address,
        $amount,
        $feePerKbyte,
        ?string $type = null
    ): array {
        $params = array_merge(
            compact('address', 'amount'),
            ['fee' => $feePerKbyte]
        );

        return $this->post($this->walletsEndpoint($type, '/withdraw'), $params);
    }

    /**
     * @param  string  $address
     * @param  string|float  $amount
     * @param  string|float  $feePerKbyte
     * @param  string|null  $type
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \O21\CryptoPaymentApi\Exceptions\ValidationException
     */
    #[ArrayShape(['amount' => 'float', 'amount_in_usd' => 'float'])]
    public function calculateFeeAmount(
        string $address,
        $amount,
        $feePerKbyte,
        ?string $type = null
    ): array {
        $params = array_merge(
            compact('address', 'amount'),
            ['fee' => $feePerKbyte]
        );

        return $this->get($this->walletsEndpoint($type, '/withdraw-fee-amount'), $params);
    }

    public function setWalletType(string $type): self
    {
        $this->walletType = $type;
        return $this;
    }

    protected function walletsEndpoint(?string $type = null, string $postfix = ''): string
    {
        $type = $this->assertWalletType($type);
        return "wallets/$type$postfix";
    }

    protected function assertWalletType(?string $type): string
    {
        if ($type) {
            return $type;
        }

        if (! $this->walletType) {
            throw WalletException::typeNotDefined();
        }

        return $this->walletType;
    }
}