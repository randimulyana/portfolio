<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);

        return [
            'category_id'       => Category::factory()->projectType(),
            'title'             => $title,
            // Slug akan di-generate otomatis oleh HasSlug — tidak perlu diisi manual
            'short_description' => $this->faker->paragraph(2),
            'long_description'  => $this->faker->paragraphs(4, true),
            'tech_stack'        => $this->faker->randomElements(
                ['Laravel', 'Livewire', 'TailwindCSS', 'Alpine.js', 'Vue.js', 'MySQL', 'REST API', 'Pest'],
                $this->faker->numberBetween(2, 5)
            ),
            'live_url'          => $this->faker->boolean(60) ? $this->faker->url() : null,
            'github_url'        => $this->faker->boolean(80) ? 'https://github.com/user/' . str()->slug($title) : null,
            'is_featured'       => false,
            'sort_order'        => $this->faker->numberBetween(1, 100),
            'status'            => ProjectStatus::Draft,
        ];
    }

    // ─── States ───────────────────────────────────────────────────────
    // Penggunaan: Project::factory()->published()->create()

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => ProjectStatus::Published,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn () => [
            'is_featured' => true,
            'status'      => ProjectStatus::Published,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => ProjectStatus::Draft,
        ]);
    }
}
