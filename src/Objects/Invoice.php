<?php

namespace O21\CryptoPaymentApi\Objects;

use O21\Support\FreeObject;

/**
 * @property-read string $id
 * @property-read string $amount
 * @property-read string $amountInWalletCurrency
 * @property-read string $address
 * @property-read \Carbon\Carbon|null $createdAt
 * @property-read \Carbon\Carbon|null $paidAt
 * @property-read string $walletType
 * @property-read string $walletSymbol
 * @property-read array{confirmed: string, unconfirmed: string} $receivedToAddress
 * @property-read array $extraData
 */
class Invoice extends FreeObject
{
    protected array $dates = ['createdAt', 'paidAt'];
}