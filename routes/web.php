<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ================= PROTECTED (ADMIN) =================
Route::middleware(['admin.auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ================= REPORTS =================
    Route::prefix('reports')->name('reports.')->group(function () {

        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/{id}', [ReportController::class, 'show'])->name('show');

        Route::post('/{id}/update-status', [ReportController::class, 'updateStatus'])
            ->name('update-status');

        Route::post('/{id}/verify', [ReportController::class, 'verify'])
            ->name('verify');

        Route::post('/{id}/unverify', [ReportController::class, 'unverify'])
            ->name('unverify');

        Route::post('/{id}/reject', [ReportController::class, 'reject'])
            ->name('reject');

        Route::delete('/{id}', [ReportController::class, 'destroy'])
            ->name('destroy')
            ->middleware('super.admin');
    });

    // ================= CATEGORIES =================
    Route::prefix('categories')->name('categories.')->group(function () {

        Route::get('/', [CategoryController::class, 'index'])->name('index');

        Route::middleware('super.admin')->group(function () {
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        });
    });

    // ================= PROFILE =================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });

    // ================= ADMINS =================
    Route::prefix('admins')->name('admins.')->middleware('super.admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\AdminController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\AdminController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\AdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\AdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [\App\Http\Controllers\AdminController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ================= USERS =================
    Route::prefix('users')->name('users.')->middleware('super.admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/block', [UserController::class, 'block'])->name('block');
        Route::post('/{id}/unblock', [UserController::class, 'unblock'])->name('unblock');
    });

    // ================= SETTINGS =================
    Route::prefix('settings')->name('settings.')->middleware('super.admin')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('clear-cache');
        Route::post('/maintenance', [SettingsController::class, 'maintenance'])->name('maintenance');
        Route::get('/database', [SettingsController::class, 'database'])->name('database');
        Route::post('/optimize', [SettingsController::class, 'optimize'])->name('optimize');
    });

    // ================= HELP =================
    Route::prefix('help')->name('help.')->group(function () {
        Route::get('/', [HelpController::class, 'index'])->name('index');
        Route::get('/documentation', [HelpController::class, 'documentation'])->name('documentation');
        Route::get('/contact', [HelpController::class, 'contact'])->name('contact');
        Route::post('/submit-ticket', [HelpController::class, 'submitTicket'])->name('submit-ticket');
    });

    // ================= LOGOUT =================
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
