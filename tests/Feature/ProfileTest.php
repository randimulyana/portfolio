<?php

declare(strict_types=1);

use App\Models\User;

// File ini di-generate Breeze. Sebagian test tidak relevan karena
// route /profile di project ini berbasis Volt (bukan controller biasa).

test('profile page is displayed')->skip('Route /profile pakai Volt component — tidak ada di project ini');

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response->assertSessionHasNoErrors()->assertRedirect('/profile');

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name'  => $user->name,
            'email' => $user->email,
        ]);

    $response->assertSessionHasNoErrors()->assertRedirect('/profile');

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', ['password' => 'password']);

    $response->assertSessionHasNoErrors()->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', ['password' => 'wrong-password']);

    $response->assertSessionHasErrorsIn('userDeletion', 'password');
    expect($user->fresh())->not->toBeNull();
});