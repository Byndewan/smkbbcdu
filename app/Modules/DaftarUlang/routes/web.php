<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:SuperAdmin|Petugas|Bendahara'])->prefix('admin/daftar-ulang')->name('du.')->group(function () {

    Route::get('/', [\App\Modules\DaftarUlang\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('bills', \App\Modules\DaftarUlang\Controllers\BillController::class)->except(['show']);
    Route::resource('transactions', \App\Modules\DaftarUlang\Controllers\TransactionController::class)->only(['index', 'show', 'update']);
    Route::get('/transactions/{id}/verification', [\App\Modules\DaftarUlang\Controllers\TransactionController::class, 'verification'])->name('transactions.verification');

});

Route::get('transactions/count-pending',[\App\Modules\DaftarUlang\Controllers\TransactionController::class, 'countPending']
)->name('transactions.count');
