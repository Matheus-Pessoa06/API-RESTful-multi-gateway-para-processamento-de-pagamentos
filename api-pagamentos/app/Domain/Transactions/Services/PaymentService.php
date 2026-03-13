<?php

namespace App\Domain\Transactions\Services;

use App\Domain\Product\Models\Product;
use App\Infraestructure\Gateway\Models\Gateway;
use App\Infraestructure\Gateway\Factories\PaymentGatewayFactory;
use App\Domain\Transactions\Models\Transaction;
use App\Domain\Client\Models\Client;
use Exception;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * 
     * * @param array 
     * @return Transaction
     * @throws Exception
     */
    public function execute(array $data): Transaction
    {
        $product = Product::findOrFail($data['product_id']);
        $totalAmount = $product->amount * $data['quantity'];

        $gateways = Gateway::where('is_active', true)
            ->orderBy('priority')
            ->get();

        if ($gateways->isEmpty()) {
            throw new Exception("Nenhum gateway de pagamento está disponível no momento.");
        }

        foreach ($gateways as $gateway) {
            try {
                $adapter = PaymentGatewayFactory::create($gateway->name);
                
                $result = $adapter->pay($totalAmount, $data);

                return DB::transaction(function () use ($data, $gateway, $product, $totalAmount, $result) {
                    
                    $client = Client::firstOrCreate(
                        ['email' => $data['email']],
                        ['name'  => $data['name']]
                    );

                    $transaction = Transaction::create([
                        'client_id'         => $client->id,
                        'gateway_id'        => $gateway->id,
                        'external_id'       => $result['external_id'],
                        'status'            => 'PAID',
                        'amount'            => $totalAmount,
                        'card_last_numbers' => substr($data['cardNumber'], -4),
                    ]);

                    $transaction->products()->attach($product->id, [
                        'quantity' => $data['quantity']
                    ]);

                    return $transaction;
                });

            } catch (Exception $e) {
                // continue;
                throw new Exception("Erro no gateway {$gateway->name}: " . $e->getMessage());
            }
        }

        throw new Exception("Falha crítica: Todos os gateways de pagamento retornaram erro.");
    }
}