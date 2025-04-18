<?php

namespace App\Services;

use GuzzleHttp\Client;

class PayPalService
{
    protected $client;
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.secret');
    }

    public function getAccessToken()
    {
        $response = $this->client->post($this->baseUrl . '/v1/oauth2/token', [
            'auth' => [$this->clientId, $this->clientSecret],
            'form_params' => ['grant_type' => 'client_credentials'],
        ]);

        return json_decode($response->getBody(), true)['access_token'];
    }

    public function createOrder($amount)
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->post($this->baseUrl . '/v2/checkout/orders', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => $amount,
                        ],
                    ],
                ],
                'application_context' => [
                    'return_url' => route('paypal.success'),
                    'cancel_url' => route('paypal.cancel'),
                    'user_action' => 'PAY_NOW',  // Evitar que el usuario revise, y hacerlo directo
                    'brand_name' => 'Mi Aplicación',
                    
                    'shipping_preference' => 'NO_SHIPPING',
                ],
            ],
        ]);
        
        return json_decode($response->getBody(), true);
    }

    public function captureOrder($orderId)
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->post($this->baseUrl . "/v2/checkout/orders/{$orderId}/capture", [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
