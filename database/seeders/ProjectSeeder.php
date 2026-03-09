<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $webCategory = Category::where('name', 'Web Application')->first();
        $apiCategory = Category::where('name', 'REST API')->first();

        // ─── Data proyek nyata (contoh portfolio) ─────────────────────
        $projects = [
            [
                'category_id'       => $webCategory?->id,
                'title'             => 'Portfolio Website',
                'short_description' => 'Aplikasi portfolio fullstack yang kamu baca sekarang. Dibangun dengan Laravel, Livewire 3, TailwindCSS, dan Alpine.js.',
                'long_description'  => '<p>Proyek ini adalah hasil latihan berkelanjutan untuk mengasah skill Laravel fullstack. Fitur utama mencakup manajemen proyek dan blog dengan panel admin yang clean.</p><h2>Teknologi</h2><p>Stack yang digunakan: Laravel 12, Livewire 3, TailwindCSS, Alpine.js, Spatie Media Library, dan MySQL.</p><h2>Tantangan</h2><p>Membangun arsitektur yang maintainable dengan Repository Pattern dan Service Layer sejak awal.</p>',
                'tech_stack'        => ['Laravel', 'Livewire', 'TailwindCSS', 'Alpine.js', 'MySQL'],
                'github_url'        => 'https://github.com/username/portfolio',
                'live_url'          => 'https://portfolio.example.com',
                'is_featured'       => true,
                'sort_order'        => 1,
                'status'            => ProjectStatus::Published,
                'tags'              => ['laravel', 'livewire', 'fullstack'],
            ],
            [
                'category_id'       => $apiCategory?->id,
                'title'             => 'REST API Manajemen Tugas',
                'short_description' => 'RESTful API untuk manajemen tugas dengan autentikasi Sanctum, resource transformation, dan dokumentasi lengkap.',
                'long_description'  => '<p>API ini dibuat sebagai latihan membangun backend yang bersih dengan Laravel. Menggunakan API Resource untuk transformasi data, Form Request untuk validasi, dan Sanctum untuk autentikasi.</p>',
                'tech_stack'        => ['Laravel', 'Sanctum', 'MySQL', 'Pest'],
                'github_url'        => 'https://github.com/username/task-api',
                'live_url'          => null,
                'is_featured'       => true,
                'sort_order'        => 2,
                'status'            => ProjectStatus::Published,
                'tags'              => ['api', 'laravel', 'sanctum'],
            ],
            [
                'category_id'       => $webCategory?->id,
                'title'             => 'Mini E-Commerce',
                'short_description' => 'Toko online sederhana sebagai latihan integrasi payment gateway dan manajemen produk.',
                'long_description'  => '<p>Proyek latihan membangun e-commerce dengan fitur keranjang belanja, checkout, dan integrasi Midtrans.</p>',
                'tech_stack'        => ['Laravel', 'Livewire', 'TailwindCSS', 'Midtrans'],
                'github_url'        => 'https://github.com/username/mini-ecommerce',
                'live_url'          => null,
                'is_featured'       => false,
                'sort_order'        => 3,
                'status'            => ProjectStatus::Draft,
                'tags'              => ['laravel', 'ecommerce'],
            ],
        ];

        foreach ($projects as $data) {
            $tags = $data['tags'] ?? [];
            unset($data['tags']);

            $project = Project::updateOrCreate(
                ['title' => $data['title']],
                $data
            );

            // Attach tags via Spatie\Tags
            if (!empty($tags)) {
                $project->syncTags($tags);
            }
        }

        // ─── Generate data dummy tambahan ─────────────────────────────
        Project::factory()
            ->count(4)
            ->published()
            ->create();

        Project::factory()
            ->count(2)
            ->draft()
            ->create();

        $this->command->info('Projects seeded.');
    }
}
