<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // ─── Kategori untuk proyek ────────────────────────────────────
        $projectCategories = [
            'Web Application',
            'REST API',
            'Mobile App',
            'Landing Page',
            'Admin Dashboard',
        ];

        foreach ($projectCategories as $name) {
            Category::updateOrCreate(
                ['name' => $name, 'type' => 'project'],
                ['name' => $name, 'type' => 'project']
            );
        }

        // ─── Kategori untuk blog ──────────────────────────────────────
        $postCategories = [
            'Laravel',
            'Tutorial',
            'Tips & Tricks',
            'Refleksi',
            'Tools',
        ];

        foreach ($postCategories as $name) {
            Category::updateOrCreate(
                ['name' => $name, 'type' => 'post'],
                ['name' => $name, 'type' => 'post']
            );
        }

        $this->command->info('Categories seeded.');
    }
}
