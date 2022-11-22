<?php

namespace O21\CryptoPaymentApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use O21\CryptoPaymentApi\Exceptions\ValidationException;
use O21\CryptoPaymentApi\Webhook\Signature;

class Client
{
    use Methods\Rates;
    use Methods\Invoices;
    use Methods\Wallets;
    use Methods\Users;

    public const API_URL = 'https://wisepay.to/api/v1/';

    private const AUTH_HEADER_KEY = 'Authorization';
    private const SIGNATURE_HEADER_KEY = 'Authorization-Signature';

    protected GuzzleClient $guzzle;

    protected string $publicKey;

    protected string $privateKey;

    public function __construct(
        string $publicKey = '',
        string $privateKey = '',
        string $url = ''
    ) {
        $this->setPublicKey($publicKey);
        $this->setPrivateKey($privateKey);

        $this->guzzle = new GuzzleClient([
            'base_uri' => $url ?: $this->urlFromEnv() ?: self::API_URL
        ]);
    }

    protected function urlFromEnv(): ?string
    {
        return class_exists('PhpOption\Option') ? env('API_URL') : null;
    }

    /**
     * @throws \O21\CryptoPaymentApi\Exceptions\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(
        string $endpoint,
        array $query = [],
        array $clientOptions = []
    ) {
        $options = array_merge(
            compact('query'),
            $clientOptions
        );

        return $this->sendRequest('GET', $endpoint, $options);
    }

    /**
     * @throws \O21\CryptoPaymentApi\Exceptions\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(
        string $endpoint,
        array $form_params = [],
        array $clientOptions = []
    ) {
        $options = array_merge(
            compact('form_params'),
            $clientOptions
        );

        return $this->sendRequest('POST', $endpoint, $options);
    }

    /**
     * @throws \O21\CryptoPaymentApi\Exceptions\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function patch(
        string $endpoint,
        array $form_params = [],
        array $clientOptions = []
    ) {
        $options = array_merge(
            compact('form_params'),
            $clientOptions
        );

        return $this->sendRequest('PATCH', $endpoint, $options);
    }

    /**
     * @throws \O21\CryptoPaymentApi\Exceptions\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array|string
     */
    public function sendRequest(string $method, string $endpoint, array $options)
    {
        $this->appendDefaultOptions($method, $options);

        $body = $this->guzzle->request($method, $endpoint, $options)->getBody();

        $response = @json_decode($body, true) ?? (string)$body;

        if (is_array($response) && ValidationException::hasInResponse($response)) {
            throw ValidationException::fromResponse($response);
        }

        return $response;
    }

    protected function appendDefaultOptions(string $method, array &$options): void
    {
        if (! isset($options[RequestOptions::HEADERS])) {
            $options[RequestOptions::HEADERS] = [];
        }

        // Authorization memo
        $options[$this->getRequestPayloadKey($method)]['memo'] = time();

        // Authorization headers
        $options[RequestOptions::HEADERS][self::AUTH_HEADER_KEY] = $this->getPublicKey();
        $options[RequestOptions::HEADERS][self::SIGNATURE_HEADER_KEY] = Signature::computeSignature(
            $this->getPrivateKey(),
            $this->getRequestPayload($method, $options)
        );

        $options[RequestOptions::HTTP_ERRORS] = false;
    }

    protected function getRequestPayload(string $method, array $options): array
    {
        return $options[$this->getRequestPayloadKey($method)] ?? [];
    }

    protected function getRequestPayloadKey(string $method): string
    {
        switch ($method) {
            case 'GET':
            default:
                return RequestOptions::QUERY;
            case 'POST':
            case 'PATCH':
                return RequestOptions::FORM_PARAMS;
        }
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @param  string  $publicKey
     */
    public function setPublicKey(string $publicKey): void
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @param  string  $privateKey
     */
    public function setPrivateKey(string $privateKey): void
    {
        $this->privateKey = $privateKey;
    }
}