<?php

use Illuminate\Support\Facades\Route;
use App\Domain\Transactions\Controllers\CheckoutController;

Route::post('/checkout', CheckoutController::class);