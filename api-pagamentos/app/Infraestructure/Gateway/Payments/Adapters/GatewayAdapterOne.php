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
        $response = Http::post("http://gateways-mock:3001/login", [
            'email' => 'dev@betalent.tech',
            'token' => 'FEC9BB078BF338F464F96B48089EB498'
        ]);

        $token = $response->json('token');

        if (is_null($token)) {
            throw new Exception("Erro de autenticação no Gateway 1");
        }

        return (string) $token;
    }
}