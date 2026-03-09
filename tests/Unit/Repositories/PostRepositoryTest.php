<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\Post;
use App\Repositories\PostRepository;

function postRepo(): PostRepository
{
    return new PostRepository(new Post);
}

describe('PostRepository', function () {

    // paginatePublished(?string $tagSlug, int $perPage)
    it('paginates published posts', function () {
        Post::factory()->published()->count(12)->create([
            'published_at' => now()->subHour(),
        ]);
        Post::factory()->draft()->count(3)->create();

        $result = postRepo()->paginatePublished(perPage: 9);

        expect($result->count())->toBe(9);
        expect($result->total())->toBe(12);
    });

    it('excludes future-scheduled posts from pagination', function () {
        Post::factory()->count(2)->create([
            'status'       => PostStatus::Published,
            'published_at' => now()->addDay(),
        ]);
        Post::factory()->published()->count(3)->create([
            'published_at' => now()->subHour(),
        ]);

        $result = postRepo()->paginatePublished(perPage: 9);

        expect($result->total())->toBe(3);
    });

    // findPublishedBySlug() — firstOrFail(), TIDAK auto-increment views
    it('finds post by slug', function () {
        $post = Post::factory()->published()->create([
            'published_at' => now()->subHour(),
        ]);

        $found = postRepo()->findPublishedBySlug($post->slug);

        expect($found)->not->toBeNull();
        expect($found->id)->toBe($post->id);
    });

    // Draft → scope published() tidak lolos → ModelNotFoundException
    it('throws ModelNotFoundException for draft post', function () {
        $draft = Post::factory()->draft()->create();

        expect(fn () => postRepo()->findPublishedBySlug($draft->slug))
            ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
    });

    it('throws ModelNotFoundException for non-existent slug', function () {
        expect(fn () => postRepo()->findPublishedBySlug('artikel-tidak-ada'))
            ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
    });

    // getRecent(int $limit) — HANYA parameter limit, tidak ada excludeId
    it('returns recent published posts up to limit', function () {
        Post::factory()->published()->count(5)->create([
            'published_at' => now()->subHour(),
        ]);

        $result = postRepo()->getRecent(limit: 3);

        expect($result)->toHaveCount(3);
    });

    it('getRecent only returns published posts', function () {
        Post::factory()->published()->count(3)->create([
            'published_at' => now()->subHour(),
        ]);
        Post::factory()->draft()->count(2)->create();

        $result = postRepo()->getRecent(limit: 10);

        expect($result)->toHaveCount(3);
    });
});
