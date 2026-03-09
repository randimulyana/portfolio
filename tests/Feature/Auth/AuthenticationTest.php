<?php

declare(strict_types=1);

use App\Models\User;

describe('Authentication', function () {

    it('shows login page', function () {
        $this->get(route('login'))->assertOk();
    });

    it('redirects authenticated user away from login page', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect();
    });

    it('logs out authenticated user via POST /logout', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect('/');

        $this->assertGuest();
    });

    it('cannot access admin after logout', function () {
        $user = User::factory()->create();

        // Logout dulu
        $this->actingAs($user)->post(route('logout'));

        // Pastikan sudah guest
        $this->assertGuest();

        // Akses admin harus redirect ke login
        $this->get(route('admin.dashboard'))
            ->assertRedirectToRoute('login');
    });
});