<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:SuperAdmin|Petugas|Bendahara'])->prefix('admin/settings')->name('settings.')->group(function () {
    Route::get('/front', [\App\Modules\Settings\Controllers\SettingsController::class, 'index'])->name('front.index');
    Route::post('/front', [\App\Modules\Settings\Controllers\SettingsController::class, 'update'])->name('front.update');
    Route::get('/front/data/{type}', [\App\Modules\Settings\Controllers\SettingsController::class, 'getData'])->name('front.data');
    Route::post('/front/feature', [\App\Modules\Settings\Controllers\SettingsController::class, 'saveFeature'])->name('front.feature.store');
    Route::delete('/front/feature/{id}', [\App\Modules\Settings\Controllers\SettingsController::class, 'destroyFeature'])->name('front.feature.destroy');
    Route::post('/front/step', [\App\Modules\Settings\Controllers\SettingsController::class, 'saveStep'])->name('front.step.store');
    Route::delete('/front/step/{id}', [\App\Modules\Settings\Controllers\SettingsController::class, 'destroyStep'])->name('front.step.destroy');
    Route::post('/front/faq', [\App\Modules\Settings\Controllers\SettingsController::class, 'saveFaq'])->name('front.faq.store');
    Route::delete('/front/faq/{id}', [\App\Modules\Settings\Controllers\SettingsController::class, 'destroyFaq'])->name('front.faq.destroy');

});
