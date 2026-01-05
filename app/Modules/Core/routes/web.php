<?php

use App\Modules\Core\Controllers\AuthController;
use App\Modules\Core\Controllers\ClassController;
use App\Modules\Core\Controllers\MajorController;
use App\Modules\Core\Controllers\RoleController;
use App\Modules\Core\Controllers\SchoolYearController;
use App\Modules\Core\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES (Login & Logout)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// ADMIN ROUTES (GUARD: WEB)
// ============================================
Route::middleware(['auth:web', 'role:SuperAdmin|Petugas|Bendahara'])->prefix('admin')->group(function () {
    Route::get('/landing', function () {
        return view('admin.landing');
    })->name('landing');

    Route::get('/', function () {
        return redirect()->route('landing');
    });

    // --- ROUTE MODUL CORE ---
    Route::prefix('core')->name('core.')->group(function () {
        Route::get('/dashboard', function () {
            return view('Core::dashboard');
        });
        Route::resource('majors', MajorController::class)->except(['show']);
        Route::post('school-years/{id}/activate', [SchoolYearController::class, 'activate'])->name('school-years.activate');
        Route::resource('school-years', SchoolYearController::class)->except(['show']);
        Route::resource('classes', ClassController::class)->except(['show']);
        Route::resource('students', StudentController::class);
        Route::post('students/import', [StudentController::class, 'import'])->name('students.import');

        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('permissions', \App\Modules\Core\Controllers\PermissionController::class)->except(['show']);
        Route::resource('users', \App\Modules\Core\Controllers\UserController::class)->except(['show']);
    });

    // --- ROUTE MODUL KEUANGAN ---
    Route::get('/keuangan/dashboard', function () {
        return view('Keuangan::dashboard');
    });

});
