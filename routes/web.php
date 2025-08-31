<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('dashboard')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::view('/', 'dashboard')->name('dashboard');
        Route::view('list/{postType}', 'list')->name('list');
        Route::match(['get', 'post'], 'editor/{postType}/{id?}', function ($postType, ?int $id = null) {
            return view('editor', [
                'postType' => $postType,
                'id' => $id,
            ]);
        })->name('editor');
    });

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::get('/{postTypeOrName}/{name?}', function ($postTypeOrName, $name = null) {
    dump([
        $postTypeOrName,
        $name,
    ]);
})->name('single');
