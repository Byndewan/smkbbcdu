<?php

use App\Http\Controllers\Api\PaymentCallbackController;
use Illuminate\Support\Facades\Route;

Route::post('payment/callback', [PaymentCallbackController::class, 'handle']);


Route::get('/test-callback', function () {
    return 'API OK';
});
