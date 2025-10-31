<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::livewire('login', 'auth.login')
        ->name('login');

    Route::livewire('register', 'auth.register')
        ->name('register');

    Route::livewire('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Route::livewire('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');

});

Route::middleware('auth')->group(function () {
    Route::livewire('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::livewire('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
});

// TODO: support CSRF in GET
Route::any('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
