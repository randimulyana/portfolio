<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $publishedAt = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'category_id'      => Category::factory()->postType(),
            'title'            => $this->faker->unique()->sentence(6),
            'content'          => $this->generateContent(),
            'excerpt'          => $this->faker->paragraph(2),
            'status'           => PostStatus::Draft,
            'published_at'     => null,
            'views'            => 0,
            'meta_title'       => null,
            'meta_description' => null,
        ];
    }

    // ─── States ───────────────────────────────────────────────────────

    public function published(): static
    {
        return $this->state(fn () => [
            'status'       => PostStatus::Published,
            'published_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'views'        => $this->faker->numberBetween(10, 500),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status'       => PostStatus::Draft,
            'published_at' => null,
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn () => [
            'status'       => PostStatus::Scheduled,
            'published_at' => $this->faker->dateTimeBetween('now', '+1 month'),
        ]);
    }

    // ─── Helper: buat konten HTML realistis ───────────────────────────
    private function generateContent(): string
    {
        $paragraphs = $this->faker->paragraphs($this->faker->numberBetween(4, 8));

        $content = "<p>{$paragraphs[0]}</p>";
        $content .= "\n\n<h2>{$this->faker->sentence(4)}</h2>";
        $content .= "\n<p>{$paragraphs[1]}</p>";
        $content .= "\n<p>{$paragraphs[2]}</p>";

        if (count($paragraphs) > 3) {
            $content .= "\n\n<h2>{$this->faker->sentence(3)}</h2>";
            $content .= "\n<p>{$paragraphs[3]}</p>";
        }

        // Tambahkan contoh code block
        $content .= "\n\n<pre><code class=\"language-php\">// Contoh kode\n\$result = collect([1, 2, 3])->map(fn(\$n) => \$n * 2);</code></pre>";

        if (count($paragraphs) > 4) {
            $content .= "\n\n<p>{$paragraphs[4]}</p>";
        }

        return $content;
    }
}
