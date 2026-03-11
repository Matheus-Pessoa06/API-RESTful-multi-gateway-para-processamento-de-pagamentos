<?php
namespace App\Infraestructure\Gateway\Factories;

use App\Infraestructure\Gateway\Contracts\PaymentGatewayInterface;
use App\Infraestructure\Gateway\Payments\Adapters\GatewayAdapterOne;
use App\Infraestructure\Gateway\Payments\Adapters\GatewayAdapterTwo;
use Exception;

class PaymentGatewayFactory
{
    public static function create(string $gatewayName): PaymentGatewayInterface
    {
        return match ($gatewayName) {
            'Gateway 1' => new GatewayAdapterOne(),
            'Gateway 2' => new GatewayAdapterTwo(),
            default => throw new Exception("Gateway [{$gatewayName}] não suportado."),
        };
    }
}