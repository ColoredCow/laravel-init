<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('authenticated user can update password', function () {
    $user = User::factory()->create(['password' => Hash::make('old-password')]);
    $this->actingAs($user);

    $response = $this->putJson('/api/profile/password', [
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Password updated successfully!']);

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('password update fails with invalid data', function () {
    $user = User::factory()->create(['password' => Hash::make('old-password')]);
    $this->actingAs($user);

    $response = $this->putJson('/api/profile/password', [
        'password' => 'short',
        'password_confirmation' => 'short',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});
