<?php

namespace O21\CryptoPaymentApi\Methods;

use O21\CryptoPaymentApi\Objects\Invoice;

trait Invoices
{
    public function createInvoice(
        string $amount,
        string $currency,
        array $extra_data = [],
        ?string $type = ''
    ): Invoice {
        $response = $this->post(
            $this->walletsEndpoint($type, '/invoices'),
            compact('amount', 'currency', 'extra_data')
        );

        return new Invoice($response);
    }

    public function getInvoice(string $id): Invoice
    {
        return new Invoice($this->get("invoices/$id"));
    }
}