<?php

namespace O21\CryptoPaymentApi\Methods;

trait Rates
{
    public function getRates(string $currency): array
    {
        return $this->get('rates', compact('currency'));
    }
}