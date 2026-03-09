<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\PostRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Daftarkan semua binding interface → implementasi di sini.
     *
     * Dengan bind(), setiap kali kode meminta ProjectRepositoryInterface
     * via constructor injection, Laravel akan inject ProjectRepository secara otomatis.
     *
     * Keuntungan: untuk testing, kita bisa swap implementasi tanpa ubah
     * satu baris pun di service atau controller.
     */
    public function register(): void
    {
        $this->app->bind(
            ProjectRepositoryInterface::class,
            ProjectRepository::class
        );

        $this->app->bind(
            PostRepositoryInterface::class,
            PostRepository::class
        );
    }
}
