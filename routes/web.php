<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\Admin\UserManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
Route::get('/', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes pour le changement de mot de passe
Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [App\Http\Controllers\Auth\PasswordChangeController::class, 'showChangeForm'])
        ->name('password.change');
    Route::post('/password/change', [App\Http\Controllers\Auth\PasswordChangeController::class, 'change'])
        ->name('password.change.submit');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Only Routes
    Route::middleware(['role:administrateur'])->group(function () {
        // Security Settings
        Route::get('/admin/security', [SecurityController::class, 'index'])->name('admin.security');
        Route::post('/admin/security/login', [SecurityController::class, 'updateLoginSettings'])->name('admin.security.login');
        Route::post('/admin/security/password', [SecurityController::class, 'updatePasswordSettings'])->name('admin.security.password');

        // User Management
        Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users');
        Route::post('/admin/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])
            ->name('admin.users.reset-password');
        Route::post('/admin/users/{user}/toggle-lock', [UserManagementController::class, 'toggleLock'])
            ->name('admin.users.toggle-lock');
    });

    // Residential Client Routes (Admin and Residential Agent)
    Route::middleware(['role:administrateur,prepose_residentiel'])->group(function () {
        Route::get('/clients/residential', [ClientController::class, 'residential'])->name('clients.residential');
    });

    // Business Client Routes (Admin and Business Agent)
    Route::middleware(['role:administrateur,prepose_affaire'])->group(function () {
        Route::get('/clients/business', [ClientController::class, 'business'])->name('clients.business');
    });
});
