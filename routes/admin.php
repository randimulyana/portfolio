<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Semua route di sini dilindungi middleware auth.
| Prefix: /admin | Name prefix: admin.
*/

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Projects
        Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);

        // Blog / Posts
        Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);

    });

    // require __DIR__.'/auth.php';