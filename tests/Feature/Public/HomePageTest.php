<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\Project;

describe('Home Page', function () {

    it('returns 200 OK', function () {
        $this->get(route('home'))->assertOk();
    });

    it('shows featured projects on homepage', function () {
        $featured = Project::factory()->published()->featured()->count(2)->create();
        Project::factory()->published()->create(['is_featured' => false]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $featured->each(fn ($p) =>
            $response->assertSee($p->title)
        );
    });

    it('does not show draft projects on homepage', function () {
        $draft = Project::factory()->draft()->create(['is_featured' => true]);

        $response = $this->get(route('home'));

        $response->assertDontSee($draft->title);
    });

    it('shows recent posts on homepage', function () {
        $posts = Post::factory()->published()->count(3)->create([
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $posts->each(fn ($p) =>
            $response->assertSee($p->title)
        );
    });

    it('does not show draft posts on homepage', function () {
        $draft = Post::factory()->draft()->create();

        $this->get(route('home'))->assertDontSee($draft->title);
    });

    it('shows empty state when no featured projects', function () {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Belum ada proyek yang ditampilkan');
    });
});
