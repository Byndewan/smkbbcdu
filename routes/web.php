<?php

use App\Http\Controllers\Front\FrontController;
use App\Mail\PaymentSuccessMail;
use App\Models\DuTransaction;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/',[FrontController::class, 'index'])->name('home');

Route::get('/test-email', function () {
    $transaction = DuTransaction::with('student')->first();

    if (!$transaction) {
        return "Belum ada data transaksi di database. Buat dulu satu!";
    }

    Mail::to('siswa@sekolah.id')->send(new PaymentSuccessMail($transaction));

    return "Email Berhasil Dikirim! Cek Mailtrap sekarang.";
});
