<?php

namespace Tests;

class InvoicesTest extends TestCase
{
    public function test_create_invoice(): void
    {
        $invoice = $this->client->createInvoice(100, 'USD', [
            'foo' => 'bar'
        ]);

        $this->assertIsString($invoice->id);
        $this->assertIsArray($invoice->extraData);
        $this->assertArrayHasKey('foo', $invoice->extraData);
    }
}