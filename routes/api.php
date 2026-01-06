<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentCallbackController;

Route::post('payment/callback', [PaymentCallbackController::class, 'handle']);

Route::get('testing', function ($id) {
    return 'TESTING OKE';
});
