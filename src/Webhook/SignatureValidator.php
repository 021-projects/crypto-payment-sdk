<?php

namespace O21\CryptoPaymentApi\Webhook;

class SignatureValidator
{
    public static function isValid(
        string $signature,
        string $signingSecret,
        string $content
    ): bool {
        $computedSignature = hash_hmac('sha256', $content, $signingSecret);

        return hash_equals($signature, $computedSignature);
    }
}