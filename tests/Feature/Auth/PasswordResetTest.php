<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    Session::start();
    $csrfToken = csrf_token();
    $this->post('/forgot-password', ['email' => $user->email, '_token' => $csrfToken,])
        ->assertStatus(200);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password link cannot be requested for invalid email', function () {
    Notification::fake();
    Session::start();
    $csrfToken = csrf_token();
    $response = $this->post('/forgot-password', [
        'email' => 'invalid@example.com',
        '_token' => $csrfToken
    ]);
    
    $response->assertStatus(302)
             ->assertSessionHasErrors('email');
});
