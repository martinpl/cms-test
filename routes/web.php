<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('dashboard')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::view('list/{postType}', 'list')->name('list');
        Route::match(['get', 'post'], 'editor/{postType}/{id?}', function ($postType, ?int $id = null) {
            return view('editor', [
                'postType' => $postType,
                'id' => $id,
            ]);
        })->name('editor');
        Route::match(['get', 'post'], 'taxonomies/{taxonomyType}/{postType}/{id?}', function ($taxonomyType, $postType, ?int $id = null) {
            return view('taxonomies', [
                'taxonomyType' => $taxonomyType,
                'postType' => $postType,
                'id' => $id,
            ]);
        })->name('taxonomies');
    });

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::get('/{postTypeOrName}/{name?}', function ($postTypeOrName, $name = null) {
    $post = Post::where('type', $postTypeOrName)->where('name', $name)->first();
    dump([
        $postTypeOrName,
        $name,
        $post->terms->pluck('type', 'title')->toArray(),
        $post->terms('tag')->pluck('title')->toArray(),
    ]);
})->name('single');
