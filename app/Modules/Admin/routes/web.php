<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:web', 'role:SuperAdmin|Petugas|Bendahara'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/profile', [\App\Modules\Admin\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/update', [\App\Modules\Admin\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [\App\Modules\Admin\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});
