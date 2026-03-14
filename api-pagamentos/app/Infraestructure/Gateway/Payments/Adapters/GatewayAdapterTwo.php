<?php
namespace App\Infraestructure\Gateway\Payments\Adapters;

use App\Infraestructure\Gateway\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class GatewayAdapterTwo implements PaymentGatewayInterface
{
    public function pay(int $amount, array $customerData): array
    {
        $url = config('services.gateway_two.url');
        $response = Http::withHeaders([
            'Gateway-Auth-Token' => config('services.gateway_two.auth_token'),
            'Gateway-Auth-Secret' => config('services.gateway_two.auth_secret')
        ])->post($url, [
            'valor'        => $amount,
            'nome'         => $customerData['name'],
            'email'        => $customerData['email'],
            'numeroCartao' => $customerData['cardNumber'],
            'cvv'          => $customerData['cvv'],
        ]);

        if ($response->failed()) {
            throw new Exception("Gateway 2 falhou.");
        }

        return ['success' => true, 'external_id' => $response->json('id')];
    }
}