<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PostController;
use App\Http\Controllers\Public\ProjectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Public
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('projects')->name('projects.')->group(function () {
    Route::get('/',       [ProjectController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProjectController::class, 'show'])->name('show');
});

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/',       [PostController::class, 'index'])->name('index');
    Route::get('/{slug}', [PostController::class, 'show'])->name('show');
});

Route::view('/about',   'public.about')->name('about');
Route::view('/contact', 'public.contact')->name('contact');

// Redirect setelah login
Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))
    ->name('dashboard')
    ->middleware(['auth', 'verified']);

// Logout — Breeze Volt tidak generate AuthenticatedSessionController,
// jadi kita handle manual dengan closure
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// Auth routes dari Breeze (login, register, dll)
require __DIR__.'/auth.php';