<?php
namespace App\Infraestructure\Gateway\Payments\Adapters;

use App\Infraestructure\Gateway\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class GatewayAdapterTwo implements PaymentGatewayInterface
{
    private string $baseUrl = 'http://gateways-mock:3002';

    public function pay(int $amount, array $customerData): array
    {
        $response = Http::withHeaders([
            'x-api-key' => 'chave-mestra-gateway-2'
        ])->post("{$this->baseUrl}/transacao", [
            'valor' => $amount,
            'cartao' => $customerData['card_number'],
            'cvv' => $customerData['cvv'],
            'validade' => $customerData['expiration_date'],
            'nome_titular' => $customerData['holder_name'],
        ]);

        if ($response->failed()) {
            throw new Exception("Gateway 2 indisponível.");
        }

        return [
            'success' => true,
            'external_id' => $response->json('id_transacao'),
        ];
    }
}