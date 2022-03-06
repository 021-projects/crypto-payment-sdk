<?php

namespace O21\CryptoPaymentApi\Exceptions;

use Exception;
use Illuminate\Support\Arr;

class ValidationException extends Exception
{
    public array $errors = [];

    public static function hasInResponse(array $response): bool
    {
        return Arr::hasAny($response, ['errors', 'exception']);
    }

    public static function fromResponse(array $response): ValidationException
    {
        $e = new ValidationException($response['message']);
        $e->errors = $response['errors'] ?? [];
        return $e;
    }
}