<?php

use App\Http\Middleware\EnsureStudentProfileCompleted;
use Illuminate\Support\Facades\Route;

Route::middleware([EnsureStudentProfileCompleted::class, 'auth:student', 'role:Siswa'])->prefix('siswa')->name('student.')->group(function () {

    Route::get('/profile', [\App\Modules\Siswa\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [\App\Modules\Siswa\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Modules\Siswa\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile.photo', [\App\Modules\Siswa\Controllers\ProfileController::class, 'updatePhoto'])->name('profile.update_photo');
    Route::get('/complete-profile', [\App\Modules\Siswa\Controllers\ProfileCompletionController::class, 'showForm'])->name('profile.complete');
    Route::post('/complete-profile', [\App\Modules\Siswa\Controllers\ProfileCompletionController::class, 'store'])->name('profile.store');

    Route::get('/dashboard', [\App\Modules\DaftarUlang\Controllers\StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tagihan/{id}', [\App\Modules\DaftarUlang\Controllers\StudentDashboardController::class, 'show'])->name('bills.show');
    Route::get('/tagihan/{id}/bayar', [\App\Modules\DaftarUlang\Controllers\StudentDashboardController::class, 'payment'])->name('bills.payment');
    Route::post('/tagihan/{id}/bayar', [\App\Modules\DaftarUlang\Controllers\StudentDashboardController::class, 'processPayment'])->name('bills.payment.process');
    Route::post('/tagihan/upload', [\App\Modules\DaftarUlang\Controllers\StudentDashboardController::class, 'uploadRequirement'])->name('upload.temp');
    Route::get('/riwayat-pembayaran', [\App\Modules\DaftarUlang\Controllers\StudentDashboardController::class, 'history'])->name('history');
    Route::get('/tagihan/{id}/invoice', [\App\Modules\DaftarUlang\Controllers\StudentDashboardController::class, 'invoice'])->name('bills.invoice');

});
