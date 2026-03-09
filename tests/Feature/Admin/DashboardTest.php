<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\Project;
use App\Models\User;

describe('Admin Dashboard', function () {

    it('shows correct project count stats', function () {
        $user = User::factory()->create();
        Project::factory()->published()->count(3)->create();
        Project::factory()->draft()->count(2)->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('5');  // total projects
        $response->assertSee('3');  // published projects
    });

    it('shows correct post count stats', function () {
        $user = User::factory()->create();
        Post::factory()->published()->count(2)->create([
            'published_at' => now()->subHour(),
        ]);
        Post::factory()->draft()->count(1)->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('3');  // total posts
        $response->assertSee('2');  // published posts
    });

    it('shows recent projects in dashboard', function () {
        $user     = User::factory()->create();
        $projects = Project::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $projects->each(fn ($p) => $response->assertSee($p->title));
    });

    it('shows recent posts in dashboard', function () {
        $user  = User::factory()->create();
        $posts = Post::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $posts->each(fn ($p) => $response->assertSee($p->title));
    });

    it('shows quick action links', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertSee(route('admin.projects.create'));
        $response->assertSee(route('admin.posts.create'));
    });
});
