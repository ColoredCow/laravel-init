<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->get('/api/profile');

    $response->assertOk()
             ->assertJson([
                 'status' => 'success',
                 'user' => [
                     'id' => $user->id,
                     'name' => $user->name,
                     'email' => $user->email,
                 ],
             ]);
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->patch('/api/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertOk()
        ->assertJson([
            'status' => 'profile-updated',
            'user' => [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
        ]);

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->patch('/api/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertOk()
        ->assertJson([
            'status' => 'profile-updated',
            'user' => [
                'name' => 'Test User',
                'email' => $user->email,
            ],
        ]);

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->delete('/api/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertOk()
        ->assertJson([
            'status' => 'account-deleted',
        ]);

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->from('/api/profile')
        ->delete('/api/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password');

    $this->assertNotNull($user->fresh());
});