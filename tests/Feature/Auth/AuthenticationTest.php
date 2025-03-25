<?php

use App\Models\User;
use Illuminate\Support\Facades\Session;

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    Session::start();
    $csrfToken = csrf_token();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        '_token' => $csrfToken,
    ]);

    $this->assertAuthenticated();
    $response->assertStatus(204);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    Session::start();
    $csrfToken = csrf_token();

    $response = $this->post('/logout', [
        '_token' => $csrfToken,
    ]);

    $this->assertGuest();
    $response->assertStatus(204);
});
