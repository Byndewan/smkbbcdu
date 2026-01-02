<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:SuperAdmin|Petugas|Bendahara'])->prefix('admin/keuangan')->name('finance.')->group(function () {

    Route::get('/', [\App\Modules\Keuangan\Controllers\FinanceVerifierController::class, 'index'])->name('index');

    Route::get('/laporan', [\App\Modules\Keuangan\Controllers\ReportController::class, 'report'])->name('report');
    Route::post('/laporan/export', [\App\Modules\Keuangan\Controllers\ReportController::class, 'export'])->name('export');

    Route::get('/{id}', [\App\Modules\Keuangan\Controllers\FinanceVerifierController::class, 'show'])->name('show');
    Route::put('/{id}', [\App\Modules\Keuangan\Controllers\FinanceVerifierController::class, 'update'])->name('update');

});
