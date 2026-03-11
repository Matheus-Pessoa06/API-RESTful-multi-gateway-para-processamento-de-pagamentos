<?php
namespace App\Infraestructure\Gateway\Contracts;

interface PaymentGatewayInterface
{
    /**
     * @param int 
     * @param array
     */
    public function pay(int $amount, array $customerData): array;
}