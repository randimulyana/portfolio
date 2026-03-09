<?php

declare(strict_types=1);

use App\Models\User;

describe('Admin Auth Guard', function () {

    // ─── Guest tidak bisa akses admin ────────────────────────────────

    it('redirects guest from admin dashboard to login', function () {
        $this->get(route('admin.dashboard'))
            ->assertRedirectToRoute('login');
    });

    it('redirects guest from admin projects to login', function () {
        $this->get(route('admin.projects.index'))
            ->assertRedirectToRoute('login');
    });

    it('redirects guest from admin posts to login', function () {
        $this->get(route('admin.posts.index'))
            ->assertRedirectToRoute('login');
    });

    it('redirects guest trying to create project to login', function () {
        $this->get(route('admin.projects.create'))
            ->assertRedirectToRoute('login');
    });

    it('redirects guest trying to create post to login', function () {
        $this->get(route('admin.posts.create'))
            ->assertRedirectToRoute('login');
    });

    // ─── User login bisa akses admin ─────────────────────────────────

    it('allows authenticated user to access admin dashboard', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk();
    });

    it('allows authenticated user to access projects index', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.projects.index'))
            ->assertOk();
    });

    it('allows authenticated user to access posts index', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.posts.index'))
            ->assertOk();
    });
});
