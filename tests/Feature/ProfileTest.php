<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $user = User::factory()->create(['password' => Hash::make('password')]);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put(route('profile.password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

// test('correct password must be provided to update password', function () {
//     $user = User::factory()->create(['password' => Hash::make('password')]);

//     $response = $this
//         ->actingAs($user)
//         ->from('/profile')
//         ->put(route('profile.password.update'), [
//             'current_password' => 'wrong-password',
//             'password' => 'new-password',
//             'password_confirmation' => 'new-password',
//         ]);

//     $response
//         ->assertSessionHasErrors(['current_password'])
//         ->assertRedirect('/profile');
// });

test('new password must be confirmed', function () {
    $user = User::factory()->create(['password' => Hash::make('password')]);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put(route('profile.password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'wrong-confirmation',
        ]);

    $response
        ->assertSessionHasErrors(['password'])
        ->assertRedirect('/profile');
});

test('password must meet minimum length requirement', function () {
    $user = User::factory()->create(['password' => Hash::make('password')]);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put(route('profile.password.update'), [
            'current_password' => 'password',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

    $response
        ->assertSessionHasErrors(['password'])
        ->assertRedirect('/profile');
});
