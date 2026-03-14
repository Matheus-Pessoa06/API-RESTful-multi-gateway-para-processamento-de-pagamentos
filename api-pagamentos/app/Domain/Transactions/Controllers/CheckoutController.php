<?php

namespace App\Domain\Transactions\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Transactions\Services\PaymentService;
use  App\Http\Requests\Transactions\CheckoutRequest;


class CheckoutController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function __invoke(CheckoutRequest $request)
    {
        $validated = $request->validated();

        try {
            $transaction = $this->paymentService->execute($validated);
            return response()->json([
                'message' => 'Compra realizada!',
                'amount' => $transaction->amount,
                'status' => $transaction->status,
                'transaction_id' => $transaction->id,
                'external_id' => $transaction->external_id,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}