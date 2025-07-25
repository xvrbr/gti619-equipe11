<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SecurityController;

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

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Only Routes
    Route::middleware(['role:administrateur'])->group(function () {
        Route::get('/admin/security', [SecurityController::class, 'index'])->name('admin.security');
        Route::post('/admin/security', [SecurityController::class, 'update'])->name('admin.security.update');
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
