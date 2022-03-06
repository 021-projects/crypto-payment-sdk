<?php

namespace O21\CryptoPaymentApi\Methods;

trait Rates
{
    public function getRates(): array
    {
        return $this->get('rates');
    }
}