<?php

declare(strict_types=1);

use App\Models\Post;

describe('Blog Page (Public)', function () {

    // ─── Index ───────────────────────────────────────────────────────

    it('shows blog index page', function () {
        $this->get(route('blog.index'))->assertOk();
    });

    it('shows only published posts', function () {
        $published = Post::factory()->published()->count(3)->create([
            'published_at' => now()->subHour(),
        ]);
        $draft = Post::factory()->draft()->create();

        $response = $this->get(route('blog.index'));

        $published->each(fn ($p) => $response->assertSee($p->title));
        $response->assertDontSee($draft->title);
    });

    it('does not show future-scheduled posts', function () {
        $future = Post::factory()->create([
            'status'       => \App\Enums\PostStatus::Published,
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('blog.index'))->assertDontSee($future->title);
    });

    it('paginates posts', function () {
        Post::factory()->published()->count(12)->create([
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get(route('blog.index'));

        $response->assertOk();
        // Halaman 1 hanya tampil 9 (perPage=9)
        $response->assertSee('page=2');
    });

    // ─── Show ────────────────────────────────────────────────────────

    it('shows post detail page', function () {
        $post = Post::factory()->published()->create([
            'content'      => '<p>Konten artikel ini cukup panjang untuk dibaca.</p>',
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get(route('blog.show', $post->slug));

        $response->assertOk();
        $response->assertSee($post->title);
    });

    it('increments view count on each visit', function () {
        $post = Post::factory()->published()->create([
            'views'        => 5,
            'published_at' => now()->subHour(),
        ]);

        $this->get(route('blog.show', $post->slug));

        expect($post->fresh()->views)->toBe(6);
    });

    it('returns 404 for draft post', function () {
        $draft = Post::factory()->draft()->create();

        $this->get(route('blog.show', $draft->slug))->assertNotFound();
    });

    it('returns 404 for non-existent post', function () {
        $this->get(route('blog.show', 'artikel-tidak-ada'))->assertNotFound();
    });

    it('shows related posts section', function () {
        $post = Post::factory()->published()->create([
            'published_at' => now()->subHour(),
        ]);
        // Related posts akan dicari dari tags atau kategori yang sama
        $related = Post::factory()->published()->count(2)->create([
            'category_id'  => $post->category_id,
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get(route('blog.show', $post->slug));

        $response->assertOk();
    });

    it('shows reading time on post detail', function () {
        $post = Post::factory()->published()->create([
            'content'      => str_repeat('kata ', 300), // ~1.5 menit
            'published_at' => now()->subHour(),
        ]);

        $this->get(route('blog.show', $post->slug))
            ->assertSee('menit');
    });
});
