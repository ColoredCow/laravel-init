<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

test('password can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/api/profile')
        ->put('/api/profile/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertStatus(200)
        ->assertJson(['message' => 'Password updated successfully!']);

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

