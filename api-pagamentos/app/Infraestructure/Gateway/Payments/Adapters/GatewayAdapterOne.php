<?php
namespace App\Infraestructure\Gateway\Payments\Adapters;

use App\Infraestructure\Gateway\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class GatewayAdapterOne implements PaymentGatewayInterface
{
    private string $baseUrl = 'http://gateways-mock:3001';

    public function pay(int $amount, array $customerData): array
    {
        $token = $this->login();

        $response = Http::withToken($token)->post("{$this->baseUrl}/api/v1/paiment", [
            'amount' => $amount,
            'card_number' => $customerData['card_number'],
            'cvv' => $customerData['cvv'],
            'expiration_date' => $customerData['expiration_date'],
            'holder_name' => $customerData['holder_name'],
        ]);

        if ($response->failed()) {
            throw new Exception("Gateway 1 indisponível.");
        }

        return [
            'success' => true,
            'external_id' => $response->json('id'),
        ];
    }

    private function login(): string
    {
        $response = Http::post("{$this->baseUrl}/api/v1/login", [
            'email' => 'dev@betalent.com',
            'token' => 'token-fake-123'
        ]);

        return $response->json('token');
    }
}