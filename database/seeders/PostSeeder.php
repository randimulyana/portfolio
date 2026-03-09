<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $laravelCategory  = Category::where('name', 'Laravel')->first();
        $tutorialCategory = Category::where('name', 'Tutorial')->first();

        // ─── Artikel nyata (konten bermakna) ──────────────────────────
        $posts = [
            [
                'category_id'  => $laravelCategory?->id,
                'title'        => 'Memahami N+1 Problem di Laravel dan Cara Mengatasinya',
                'content'      => '<p>N+1 problem adalah salah satu masalah performa paling umum di aplikasi yang menggunakan ORM seperti Eloquent. Masalah ini terjadi ketika kamu menjalankan 1 query untuk mengambil daftar data, lalu N query tambahan untuk setiap item dalam daftar tersebut.</p><h2>Contoh N+1</h2><pre><code class="language-php">// ❌ Ini N+1 — akan menjalankan 1 + N query\n$posts = Post::all();\nforeach ($posts as $post) {\n    echo $post->category->name; // query baru untuk setiap post!\n}</code></pre><h2>Solusi: Eager Loading</h2><pre><code class="language-php">// ✅ Ini efisien — hanya 2 query\n$posts = Post::with("category")->get();\nforeach ($posts as $post) {\n    echo $post->category->name; // sudah di-load sebelumnya\n}</code></pre><p>Gunakan Laravel Debugbar untuk mendeteksi N+1 secara visual selama development.</p>',
                'excerpt'      => 'Pelajari apa itu N+1 problem di Laravel Eloquent dan bagaimana mengatasinya dengan eager loading yang tepat.',
                'status'       => \App\Enums\PostStatus::Published,
                'published_at' => now()->subDays(10),
                'views'        => 128,
                'tags'         => ['laravel', 'eloquent', 'performa'],
            ],
            [
                'category_id'  => $tutorialCategory?->id,
                'title'        => 'Setup Laravel Livewire dengan Komponen Reusable',
                'content'      => '<p>Livewire 3 membuat kita bisa membangun UI dinamis tanpa JavaScript manual. Di artikel ini kita akan belajar membuat komponen Livewire yang reusable dan maintainable.</p><h2>Prinsip Utama</h2><p>Setiap komponen Livewire sebaiknya punya satu tanggung jawab — jangan campur logic form dengan logic tabel dalam satu komponen.</p><p>Manfaatkan <code>wire:model.live</code> untuk real-time validation, dan <code>wire:loading</code> untuk feedback visual yang baik.</p>',
                'excerpt'      => 'Cara membuat komponen Livewire yang reusable, dengan tips wire:model, wire:loading, dan validasi real-time.',
                'status'       => \App\Enums\PostStatus::Published,
                'published_at' => now()->subDays(5),
                'views'        => 74,
                'tags'         => ['laravel', 'livewire', 'tutorial'],
            ],
            [
                'category_id'  => $laravelCategory?->id,
                'title'        => 'Repository Pattern di Laravel: Kapan Perlu, Kapan Tidak',
                'content'      => '<p>Repository Pattern sering dibahas di komunitas Laravel, tapi juga sering disalahgunakan. Di artikel ini saya berbagi pengalaman kapan pattern ini benar-benar membantu.</p><h2>Kapan Pakai Repository</h2><p>Gunakan repository ketika query database cukup kompleks, digunakan di banyak tempat, atau kamu perlu mocking saat testing.</p><h2>Kapan Tidak Perlu</h2><p>Untuk project kecil atau CRUD sederhana, repository bisa menjadi over-engineering. Laravel Eloquent sudah sangat expressif — tidak perlu dibungkus lagi jika tidak ada kebutuhan nyata.</p>',
                'excerpt'      => 'Diskusi jujur tentang kapan Repository Pattern benar-benar membantu di Laravel, dan kapan ia menjadi over-engineering.',
                'status'       => \App\Enums\PostStatus::Draft,
                'published_at' => null,
                'views'        => 0,
                'tags'         => ['laravel', 'arsitektur', 'best-practice'],
            ],
        ];

        foreach ($posts as $data) {
            $tags = $data['tags'] ?? [];
            unset($data['tags']);

            $post = Post::updateOrCreate(
                ['title' => $data['title']],
                $data
            );

            if (!empty($tags)) {
                $post->syncTags($tags);
            }
        }

        // ─── Data dummy tambahan ──────────────────────────────────────
        Post::factory()->count(5)->published()->create();
        Post::factory()->count(2)->draft()->create();

        $this->command->info('Posts seeded.');
    }
}
