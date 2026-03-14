<?php
namespace App\Infraestructure\Gateway\Payments\Adapters;

use App\Infraestructure\Gateway\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class GatewayAdapterOne implements PaymentGatewayInterface
{
    public function pay(int $amount, array $customerData): array
    {
        $token = $this->login();

        $response = Http::withToken($token)->post("http://gateways-mock:3001/transactions", [
            'amount'    => $amount,
            'name'      => $customerData['name'],
            'email'     => $customerData['email'],
            'cardNumber'=> $customerData['cardNumber'],
            'cvv'       => $customerData['cvv'],
        ]);

        if ($response->failed()) {
            throw new Exception("Gateway 1 falhou.");
        }

        return ['success' => true, 'external_id' => $response->json('id')];
    }

    private function login(): string
    {
        $url = config('services.gateway_one.url') . "/login";
        
        $response = Http::post($url, [
            'email' => config('services.gateway_one.email'),
            'token' => config('services.gateway_one.token')
        ]);

        $token = $response->json('token');

        if (is_null($token)) {
            throw new Exception("Erro de autenticação no Gateway 1");
        }

        return (string) $token;
    }
}