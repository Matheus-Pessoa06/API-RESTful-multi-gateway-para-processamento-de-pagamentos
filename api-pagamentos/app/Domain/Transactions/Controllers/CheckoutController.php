<?php

namespace App\Domain\Transactions\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Transactions\Services\PaymentService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'name'       => 'required|string', 
            'email'      => 'required|email', 
            'cardNumber' => 'required|string|size:16',
            'cvv'        => 'required|string',
        ]);

        try {
            $transaction = $this->paymentService->execute($validated);

            return response()->json([
                'message' => 'Compra realizada!',
                'transaction_id' => $transaction->id,
                'external_id' => $transaction->external_id
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}