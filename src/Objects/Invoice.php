<?php

namespace O21\CryptoPaymentApi\Objects;

use O21\Support\FreeObject;

/**
 * @property-read string $id
 * @property-read string $amount
 * @property-read string $amountInWalletCurrency
 * @property-read string $address
 * @property-read \Carbon\Carbon $createdAt
 * @property-read \Carbon\Carbon $paidAt
 * @property-read array $extraData
 */
class Invoice extends FreeObject
{
    protected array $dates = ['createdAt', 'paidAt'];
}