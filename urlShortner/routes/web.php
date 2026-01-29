<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| Authentication routes (login, logout, etc.) are loaded from auth.php
|
*/

// Homepage - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
| All routes requiring authentication (login required)
*/
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Short URLs (Admin, Member, Sales, Manager can create)
    Route::resource('short-urls', ShortUrlController::class)->only([
        'index', 'create', 'store', 'destroy'
    ]);

    // Invitations (SuperAdmin and Admin can invite)
    Route::resource('invitations', InvitationController::class)->only([
        'index', 'create', 'store'
    ]);
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Routes accessible without authentication
*/

// Public invitation acceptance (no auth required)
Route::get('/invitations/accept/{token}', [InvitationController::class, 'accept'])
    ->name('invitations.accept');
Route::post('/invitations/accept/{token}', [InvitationController::class, 'acceptStore'])
    ->name('invitations.accept.store');

// Public redirect route for short URLs (must be at the end to not conflict)
Route::get('/{shortCode}', [RedirectController::class, 'redirect'])
    ->where('shortCode', '[A-Za-z0-9]{6}')
    ->name('redirect');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Includes: login, logout, register, password reset
| Provided by Laravel Breeze in routes/auth.php
*/
require __DIR__.'/auth.php';
