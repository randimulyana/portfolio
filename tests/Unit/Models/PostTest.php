<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\Post;

describe('Post Model', function () {

    // ─── Reading Time ─────────────────────────────────────────────────

    it('calculates reading time from content', function () {
        $content = str_repeat('kata ', 400); // 400 kata ÷ 200 wpm = 2 menit
        $post    = Post::factory()->create(['content' => $content]);

        expect($post->reading_time)->toBe(2);
    });

    it('returns minimum 1 minute reading time for short content', function () {
        $post = Post::factory()->create(['content' => 'Artikel pendek.']);

        expect($post->reading_time)->toBe(1);
    });

    it('reading time handles whitespace content safely', function () {
        // content kolom NOT NULL — pakai spasi bukan null
        $post = Post::factory()->create(['content' => ' ']);

        expect($post->reading_time)->toBe(1);
    });

    // ─── Excerpt Text ─────────────────────────────────────────────────

    it('excerpt_text returns excerpt when set', function () {
        $post = Post::factory()->create([
            'excerpt' => 'Ini excerptnya.',
            'content' => '<p>Ini konten panjang sekali.</p>',
        ]);

        expect($post->excerpt_text)->toBe('Ini excerptnya.');
    });

    it('excerpt_text fallback to stripped content when excerpt is null', function () {
        $post = Post::factory()->create([
            'excerpt' => null,
            'content' => '<p>' . str_repeat('kata ', 50) . '</p>',
        ]);

        expect($post->excerpt_text)->not->toContain('<p>');
        expect(strlen($post->excerpt_text))->toBeLessThanOrEqual(163);
    });

    it('excerpt_text handles content with only whitespace', function () {
        $post = Post::factory()->create([
            'excerpt' => null,
            'content' => '   ',
        ]);

        expect($post->excerpt_text)->toBeString();
    });

    // ─── incrementViews ───────────────────────────────────────────────

    it('incrementViews increases view count', function () {
        $post = Post::factory()->published()->create(['views' => 10]);

        $post->incrementViews();

        expect($post->fresh()->views)->toBe(11);
    });

    it('incrementViews can be called multiple times', function () {
        $post = Post::factory()->published()->create(['views' => 0]);

        $post->incrementViews();
        $post->incrementViews();
        $post->incrementViews();

        expect($post->fresh()->views)->toBe(3);
    });

    // ─── Scopes ──────────────────────────────────────────────────────

    it('published scope only returns published posts with past publish date', function () {
        Post::factory()->published()->count(3)->create([
            'published_at' => now()->subDay(),
        ]);
        Post::factory()->draft()->count(2)->create();
        Post::factory()->create([
            'status'       => PostStatus::Published,
            'published_at' => now()->addDay(),
        ]);

        $results = Post::published()->get();

        expect($results)->toHaveCount(3);
    });

    // ─── Soft Delete & Route Key ──────────────────────────────────────

    it('soft deletes post', function () {
        $post = Post::factory()->create();
        $id   = $post->id;

        $post->delete();

        expect(Post::find($id))->toBeNull();
        expect(Post::withTrashed()->find($id))->not->toBeNull();
    });

    it('uses slug as route key', function () {
        $post = Post::factory()->create();

        expect($post->getRouteKeyName())->toBe('slug');
    });
});