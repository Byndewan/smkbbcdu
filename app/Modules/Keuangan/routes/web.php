<?php

use App\Events\PaymentReceived;
use App\Models\DuTransaction;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:SuperAdmin|Petugas|Bendahara'])->prefix('admin/keuangan')->name('finance.')->group(function () {

    Route::get('/dashboard', [\App\Modules\DaftarUlang\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/verifikasi', [\App\Modules\Keuangan\Controllers\FinanceVerifierController::class, 'index'])->name('index');

    Route::get('/laporan', [\App\Modules\Keuangan\Controllers\ReportController::class, 'report'])->name('report');
    Route::post('/laporan/export', [\App\Modules\Keuangan\Controllers\ReportController::class, 'export'])->name('export');

    Route::get('/{id}', [\App\Modules\Keuangan\Controllers\FinanceVerifierController::class, 'show'])->name('show');
    Route::put('/{id}', [\App\Modules\Keuangan\Controllers\FinanceVerifierController::class, 'update'])->name('update');

    Route::get('/transactions/{id}/verification', [\App\Modules\Keuangan\Controllers\FinanceVerifierController::class, 'verification'])->name('transaction.verification');

});

Route::get('/test-reverb', function () {
    $trx = DuTransaction::first();

    if (!$trx) {
        return "Gagal: Tabel du_transactions masih kosong. Bikin dummy dulu 1 biji.";
    }

    PaymentReceived::dispatch($trx, "TES 123! INI PERCOBAAN DARI SERVER", 'success');

    return "Sinyal sudah dikirim! Cek Console Browser Admin.";
});
