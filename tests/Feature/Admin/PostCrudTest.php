<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;

describe('Admin Post CRUD', function () {

    // ─── Index ───────────────────────────────────────────────────────

    it('shows all posts in admin table', function () {
        $user  = User::factory()->create();
        $posts = Post::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('admin.posts.index'));

        $response->assertOk();
        $posts->each(fn ($p) => $response->assertSee($p->title));
    });

    // ─── Create ──────────────────────────────────────────────────────

    it('shows create post form', function () {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.posts.create'))
            ->assertOk();
    });

    it('stores a new post', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.posts.store'), [
                'title'   => 'Artikel Pertama',
                'content' => str_repeat('konten artikel ini. ', 10),
                'status'  => PostStatus::Draft->value,
            ])
            ->assertRedirectToRoute('admin.posts.index');

        $this->assertDatabaseHas('posts', ['title' => 'Artikel Pertama']);
    });

    it('auto sets published_at when storing with Published status', function () {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.posts.store'), [
            'title'   => 'Artikel Langsung Publish',
            'content' => str_repeat('konten artikel ini. ', 10),
            'status'  => PostStatus::Published->value,
        ]);

        $post = Post::where('title', 'Artikel Langsung Publish')->first();
        expect($post->published_at)->not->toBeNull();
    });

    it('validates required fields on store', function () {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.posts.store'), [])
            ->assertSessionHasErrors(['title', 'content', 'status']);
    });

    it('validates minimum content length', function () {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.posts.store'), [
                'title'   => 'Judul',
                'content' => 'Terlalu pendek',  // kurang dari 50 karakter
                'status'  => PostStatus::Draft->value,
            ])
            ->assertSessionHasErrors(['content']);
    });

    it('validates meta_title max 60 characters', function () {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.posts.store'), [
                'title'      => 'Judul',
                'content'    => str_repeat('konten artikel ini. ', 5),
                'status'     => PostStatus::Draft->value,
                'meta_title' => str_repeat('a', 61), // 61 karakter
            ])
            ->assertSessionHasErrors(['meta_title']);
    });

    // ─── Update ──────────────────────────────────────────────────────

    it('updates an existing post', function () {
        $post = Post::factory()->create(['title' => 'Judul Lama']);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.posts.update', $post), [
                'title'   => 'Judul Diperbarui',
                'content' => str_repeat('konten baru. ', 10),
                'status'  => PostStatus::Published->value,
            ])
            ->assertRedirectToRoute('admin.posts.index');

        expect($post->fresh()->title)->toBe('Judul Diperbarui');
        expect($post->fresh()->status)->toBe(PostStatus::Published);
    });

    // ─── Delete ──────────────────────────────────────────────────────

    it('soft deletes a post', function () {
        $post = Post::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirectToRoute('admin.posts.index');

        expect(Post::find($post->id))->toBeNull();
        expect(Post::withTrashed()->find($post->id))->not->toBeNull();
    });
});
