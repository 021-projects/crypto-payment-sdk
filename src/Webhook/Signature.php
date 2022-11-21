<?php

namespace O21\CryptoPaymentApi\Webhook;

class Signature
{
    /**
     * @param  string  $signature
     * @param  string  $signingSecret
     * @param  array|string $content
     * @return bool
     * @throws \JsonException
     */
    public static function validateSignature(
        string $signature,
        string $signingSecret,
        $content
    ): bool {
        if (is_array($content)) {
            $content = json_encode($content, JSON_THROW_ON_ERROR);
        }
        $computedSignature = hash_hmac('sha256', $content, $signingSecret);
        return hash_equals($signature, $computedSignature);
    }

    /**
     * @param  string  $signingSecret
     * @param  array|string $content
     * @return string
     * @throws \JsonException
     */
    public static function computeSignature(
        string $signingSecret,
        $content
    ): string {
        if (is_array($content)) {
            $content = json_encode($content, JSON_THROW_ON_ERROR);
        }
        return hash_hmac('sha256', $content, $signingSecret);
    }
}