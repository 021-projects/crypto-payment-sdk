<?php

namespace O21\CryptoPaymentApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use O21\CryptoPaymentApi\Exceptions\ValidationException;

class Client
{
    use Methods\Rates;
    use Methods\Invoices;
    use Methods\Wallets;

    public const API_URL = 'https://crypto-payment.com/api/v1/';

    protected GuzzleClient $guzzle;

    protected string $apiToken;

    public function __construct(
        string $apiToken,
        string $url = ''
    ) {
        $this->apiToken = $apiToken;
        $this->guzzle = new GuzzleClient([
            'base_uri' => $url ?: env('API_URL') ?: self::API_URL
        ]);
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
    public function sendRequest(string $method, string $endpoint, array $options): mixed
    {
        $this->appendDefaultOptions($method, $options);

        $body = $this->guzzle->request($method, $endpoint, $options)->getBody();

        $response = @\GuzzleHttp\Utils::jsonDecode($body, true) ?? (string)$body;

        if (is_array($response) && ValidationException::hasInResponse($response)) {
            throw ValidationException::fromResponse($response);
        }

        return $response;
    }

    protected function appendDefaultOptions(string $method, array &$options): void
    {
        switch ($method) {
            case 'GET':
                if (! isset($options[RequestOptions::QUERY])) {
                    $options[RequestOptions::QUERY] = [];
                }

                $options[RequestOptions::QUERY]['api_token'] = $this->apiToken;
                break;

            case 'POST':
                if (! isset($options[RequestOptions::FORM_PARAMS])) {
                    $options[RequestOptions::FORM_PARAMS] = [];
                }

                $options[RequestOptions::FORM_PARAMS]['api_token'] = $this->apiToken;
                break;
        }

        $options[RequestOptions::HTTP_ERRORS] = false;
    }
}