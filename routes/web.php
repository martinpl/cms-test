<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::view('list/{postType}', 'list')->name('list');
        Route::view('editor/{postType}/{id?}', 'editor')->name('editor');
        Route::livewire('taxonomies/{taxonomyType}/{postType}/{id?}', 'taxonomies')->name('taxonomies');
    });

Route::view('zoo', 'zoo');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', 'settings.profile')->name('settings.profile');
    Route::livewire('settings/password', 'settings.password')->name('settings.password');
    Route::livewire('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
