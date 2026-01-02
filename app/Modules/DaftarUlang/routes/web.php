<?php

use App\Modules\DaftarUlang\Controllers\BillController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:SuperAdmin|Petugas|Bendahara'])->prefix('admin/daftar-ulang')->name('du.')->group(function () {

    Route::get('/', [\App\Modules\DaftarUlang\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('bills', BillController::class)->except(['show']);
    Route::get(
        'transactions/count-pending',
        [\App\Modules\DaftarUlang\Controllers\TransactionController::class, 'countPending']
    )->name('transactions.count');
    Route::resource('transactions', \App\Modules\DaftarUlang\Controllers\TransactionController::class)->only(['index', 'show', 'update']);

});
