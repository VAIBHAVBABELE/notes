<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisterController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('login', [LoginController::class, 'store']);

    // Google OAuth Routes
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])
        ->name('google.login');

    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

    // Password Reset Routes
    Route::get('password', [PasswordController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordController::class, 'store'])
        ->name('password.email');

    Route::get('password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

Route::middleware('auth')->group(function () {
    // Email Verification Routes
    Route::get('verification', [VerificationController::class, 'create'])
        ->name('verification.notice');

    Route::get('verification/{id}/{hash}', VerificationController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [VerificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Password Confirmation
    Route::get('password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('password', [ConfirmablePasswordController::class, 'store']);

    // Password Update
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Logout
    Route::post('logout', [LoginController::class, 'destroy'])
        ->name('logout');
});