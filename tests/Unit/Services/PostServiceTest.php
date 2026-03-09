<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\Post;
use App\Repositories\PostRepository;
use App\Services\PostService;

function makePostService(): PostService
{
    return new PostService(new PostRepository(new Post));
}

describe('PostService', function () {

    // ─── Create ──────────────────────────────────────────────────────

    it('creates a post', function () {
        $data = [
            'title'   => 'Artikel Test',
            'content' => str_repeat('kata ', 50),
            'status'  => PostStatus::Draft->value,
        ];

        $post = makePostService()->create($data);

        expect($post)->toBeInstanceOf(Post::class);
        expect($post->title)->toBe('Artikel Test');
        expect(Post::count())->toBe(1);
    });

    it('auto sets published_at when status is Published and no date given', function () {
        $data = [
            'title'   => 'Artikel Publish',
            'content' => str_repeat('kata ', 50),
            'status'  => PostStatus::Published->value,
            // tidak ada published_at
        ];

        $post = makePostService()->create($data);

        expect($post->published_at)->not->toBeNull();
        // Pastikan waktunya sekitar sekarang (toleransi 5 detik)
        expect($post->published_at->diffInSeconds(now()))->toBeLessThan(5);
    });

    it('does not override published_at if already set', function () {
        $specificDate = now()->subDays(7);

        $data = [
            'title'        => 'Artikel Tanggal Spesifik',
            'content'      => str_repeat('kata ', 50),
            'status'       => PostStatus::Published->value,
            'published_at' => $specificDate,
        ];

        $post = makePostService()->create($data);

        expect($post->published_at->toDateString())->toBe($specificDate->toDateString());
    });

    it('does not set published_at for draft posts', function () {
        $data = [
            'title'   => 'Draft Artikel',
            'content' => str_repeat('kata ', 50),
            'status'  => PostStatus::Draft->value,
        ];

        $post = makePostService()->create($data);

        expect($post->published_at)->toBeNull();
    });

    // ─── Publish / Unpublish ─────────────────────────────────────────

    it('publishes a draft post and sets published_at', function () {
        $post = Post::factory()->draft()->create(['published_at' => null]);

        $updated = makePostService()->publish($post);

        expect($updated->status)->toBe(PostStatus::Published);
        expect($updated->published_at)->not->toBeNull();
    });

    it('re-publish keeps original published_at', function () {
        $originalDate = now()->subMonth();
        $post = Post::factory()->create([
            'status'       => PostStatus::Draft,
            'published_at' => $originalDate,
        ]);

        $updated = makePostService()->publish($post);

        expect($updated->published_at->toDateString())->toBe($originalDate->toDateString());
    });

    it('unpublishes a post back to draft', function () {
        $post = Post::factory()->published()->create();

        $updated = makePostService()->unpublish($post);

        expect($updated->status)->toBe(PostStatus::Draft);
    });

    // ─── Delete ──────────────────────────────────────────────────────

    it('soft deletes a post', function () {
        $post = Post::factory()->create();
        $id = $post->id;

        makePostService()->delete($post);

        expect(Post::find($id))->toBeNull();
        expect(Post::withTrashed()->find($id))->not->toBeNull();
    });

    // ─── Tags ────────────────────────────────────────────────────────

    it('creates post with tags', function () {
        $data = [
            'title'   => 'Artikel Bertag',
            'content' => str_repeat('kata ', 50),
            'status'  => PostStatus::Draft->value,
            'tags'    => ['laravel', 'tips'],
        ];

        $post = makePostService()->create($data);

        expect($post->tags)->toHaveCount(2);
    });
});
