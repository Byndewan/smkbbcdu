<?php

use App\Http\Controllers\Api\AuthStudentController;
use App\Http\Controllers\Api\StudentMainController;
use Illuminate\Support\Facades\Route;

Route::prefix('student')->group(function () {
    Route::post('/login', [AuthStudentController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthStudentController::class, 'me']);
        Route::post('/logout', [AuthStudentController::class, 'logout']);

        Route::get('/dashboard', [StudentMainController::class, 'dashboard']);
        Route::get('/bills/{id}', [StudentMainController::class, 'showBill']);
        Route::post('/upload', [StudentMainController::class, 'uploadDocument']);
        Route::get('/payment/{id}', [StudentMainController::class, 'getPaymentData']);
        Route::post('/pay', [StudentMainController::class, 'processPayment']);
        Route::post('/resubmit/{id}', [StudentMainController::class, 'resubmit']);
        Route::get('/invoice/{id}', [StudentMainController::class, 'invoice']);
    });

});

Route::get('testing', function () {
    return response()->json([
        'success' => true,
        'message' => 'Azlia Cantik',
    ]);
});
