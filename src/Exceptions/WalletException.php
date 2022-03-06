<?php

namespace O21\CryptoPaymentApi\Exceptions;

use Exception;

class WalletException extends Exception
{
    public static function typeNotDefined(): self
    {
        return new static('Error: Wallet type is not defined. Define it via setWalletType() or in function param.');
    }
}