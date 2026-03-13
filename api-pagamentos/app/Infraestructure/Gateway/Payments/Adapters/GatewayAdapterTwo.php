<?php
namespace App\Infraestructure\Gateway\Payments\Adapters;

use App\Infraestructure\Gateway\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class GatewayAdapterTwo implements PaymentGatewayInterface
{
    public function pay(int $amount, array $customerData): array
    {
        $response = Http::withHeaders([
            'Gateway-Auth-Token' => 'tk_f2198cc671b5289fa856',
            'Gateway-Auth-Secret' => '3d15e8ed6131446ea7e3456728b1211f'
        ])->post("http://gateways-mock:3002/transacoes", [
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