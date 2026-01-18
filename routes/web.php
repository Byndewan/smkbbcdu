<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Front\FrontController;

// Core Modules
use App\Modules\Core\Controllers\AuthController;
use App\Modules\Core\Controllers\UserController;
use App\Modules\Core\Controllers\RoleController;
use App\Modules\Core\Controllers\PermissionController;
use App\Modules\Core\Controllers\MajorController;
use App\Modules\Core\Controllers\ClassController;
use App\Modules\Core\Controllers\StudentController;
use App\Modules\Core\Controllers\SchoolYearController;

// Siswa Modules
use App\Modules\Siswa\Controllers\ProfileController as StudentProfileController;
use App\Modules\Siswa\Controllers\ProfileCompletionController;
use App\Modules\DaftarUlang\Controllers\StudentDashboardController;

// Finance & Daftar Ulang
use App\Modules\DaftarUlang\Controllers\AdminDashboardController;
use App\Modules\DaftarUlang\Controllers\BillController;
use App\Modules\DaftarUlang\Controllers\TransactionController;
use App\Modules\Keuangan\Controllers\FinanceVerifierController;
use App\Modules\Keuangan\Controllers\ReportController;
use App\Modules\Settings\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES & GUEST
|--------------------------------------------------------------------------
*/

Route::get('/', [FrontController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 2. SISWA ROUTES (Guard: student)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:student', 'role:Siswa'])->prefix('siswa')->name('student.')->group(function () {

    // Profile Completion (Buat siswa yang baru login)
    Route::get('/lengkapi-profil', [ProfileCompletionController::class, 'showForm'])->name('profile.complete');
    Route::post('/lengkapi-profil', [ProfileCompletionController::class, 'store'])->name('profile.store');

    // Middleware check profile completed
    Route::middleware([\App\Http\Middleware\EnsureStudentProfileCompleted::class])->group(function () {

        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

        // Profile Management
        Route::prefix('profil')->name('profile.')->group(function() {
            Route::get('/', [StudentProfileController::class, 'index'])->name('index');
            Route::put('/update', [StudentProfileController::class, 'update'])->name('update');
            Route::put('/password', [StudentProfileController::class, 'updatePassword'])->name('password');
            Route::put('/photo', [StudentProfileController::class, 'updatePhoto'])->name('update_photo');
        });

        // Tagihan & Pembayaran
        Route::prefix('tagihan')->name('bills.')->group(function() {
            Route::get('/{id}', [StudentDashboardController::class, 'show'])->name('show');
            Route::get('/{id}/bayar', [StudentDashboardController::class, 'payment'])->name('payment');
            Route::post('/{id}/bayar', [StudentDashboardController::class, 'processPayment'])->name('payment.process');
            Route::post('/upload-dokumen', [StudentDashboardController::class, 'uploadRequirement'])->name('upload');
            Route::get('/{id}/invoice', [StudentDashboardController::class, 'invoice'])->name('invoice');
            Route::post('/{id}/resubmit', [StudentDashboardController::class, 'resubmit'])->name('resubmit');
        });

        Route::get('/riwayat', [StudentDashboardController::class, 'history'])->name('history');
    });
});

/*
|--------------------------------------------------------------------------
| 3. ADMIN ROUTES (Guard: web)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'role:SuperAdmin|Petugas|Bendahara'])->prefix('admin')->name('admin.')->group(function () {

    Route::redirect('/', '/admin/dashboard');

    // --- Landing Admin ---
    Route::get('/landing', function () {return view('admin.landing');})->name('landing');

    // --- A. MODULE MASTER DATA ---
    Route::prefix('core')->name('core.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'indexCore'])->name('dashboard');
        Route::get('/dashboard/detail-data', [AdminDashboardController::class, 'getDetailDataCore'])->name('dashboard.detail.core');
        Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
        Route::post('school-years/{id}/activate', [SchoolYearController::class, 'activate'])->name('school-years.activate');
        Route::get('students/{id}/delete-confirm', [StudentController::class, 'deleteConfirm'])->name('students.delete_confirm');
        Route::resource('majors', MajorController::class)->except(['show']);
        Route::resource('school-years', SchoolYearController::class)->except(['show']);
        Route::resource('classes', ClassController::class)->except(['show']);
        Route::resource('students', StudentController::class);
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('permissions', PermissionController::class)->except(['show']);
    });

    // --- B. MODULE DAFTAR ULANG ---
    Route::prefix('daftar-ulang')->name('du.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/detail-data', [AdminDashboardController::class, 'getDetailData'])->name('dashboard.detail');
        Route::resource('bills', BillController::class)->except(['show']);
        Route::get('transactions/count', [TransactionController::class, 'countPending'])->name('transactions.count');
        Route::get('transactions/{id}/verification', [TransactionController::class, 'verification'])->name('transactions.verification');
        Route::resource('transactions', TransactionController::class)->only(['index', 'show', 'update']);
    });

    // --- C. MODULE KEUANGAN ---
    Route::prefix('keuangan')->name('finance.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'indexFinance'])->name('dashboard');
        Route::get('/dashboard/detail-data', [AdminDashboardController::class, 'getDetailDataFinance'])->name('dashboard.detail.finance');
        Route::get('/verifikasi', [FinanceVerifierController::class, 'index'])->name('index');
        Route::get('/verifikasi/{id}', [FinanceVerifierController::class, 'verification'])->name('verification');
        Route::put('/verifikasi/{id}', [FinanceVerifierController::class, 'update'])->name('update');
        Route::get('/laporan', [ReportController::class, 'report'])->name('report');
        Route::post('/laporan/export', [ReportController::class, 'export'])->name('export');
    });

    // --- D. MODULE SETTINGS ---
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', [\App\Modules\Admin\Controllers\AdminProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/update', [\App\Modules\Admin\Controllers\AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [\App\Modules\Admin\Controllers\AdminProfileController::class, 'updatePassword'])->name('profile.password');
        Route::prefix('front')->name('front.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::post('/', [SettingsController::class, 'update'])->name('update');
            Route::get('/data/{type}', [SettingsController::class, 'getData'])->name('data');
            Route::post('/feature', [SettingsController::class, 'saveFeature'])->name('feature.store');
            Route::delete('/feature/{id}', [SettingsController::class, 'destroyFeature'])->name('feature.destroy');
            Route::post('/step', [SettingsController::class, 'saveStep'])->name('step.store');
            Route::delete('/step/{id}', [SettingsController::class, 'destroyStep'])->name('step.destroy');
            Route::post('/faq', [SettingsController::class, 'saveFaq'])->name('faq.store');
            Route::delete('/faq/{id}', [SettingsController::class, 'destroyFaq'])->name('faq.destroy');
        });
    });
});

/*
|--------------------------------------------------------------------------
| 4. WEBHOOKS & TESTING (No Auth / Specific Logic)
|--------------------------------------------------------------------------
*/

Route::get('/test-email', function () {
    $trx = \App\Models\DuTransaction::with('student')->first();
    if (!$trx) return "Buat dummy transaksi dulu.";
    \Illuminate\Support\Facades\Mail::to('dev@example.com')->send(new \App\Mail\PaymentSuccessMail($trx));
    return "Email sent!";
});

Route::get('/test-reverb', function () {
    $trx = \App\Models\DuTransaction::first();
    if (!$trx) return "Buat dummy transaksi dulu.";
    \App\Events\PaymentReceived::dispatch($trx, "TES ROUTE BARU!", 'success');
    return "Sinyal Reverb dikirim!";
});

Route::get('/force-logout', function () {
    \Illuminate\Support\Facades\Auth::guard('web')->logout();
    \Illuminate\Support\Facades\Auth::guard('student')->logout();
    session()->flush();
    return redirect('/login');
});
