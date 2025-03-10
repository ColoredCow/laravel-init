<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

test('reset password link can be requested', function () {
    $this->withoutMiddleware();
    Notification::fake();

    $user = User::factory()->create();

    // $response = $this->postJson(route('/forgot-password'), ['email' => $user->email]);
    $response = $this->postJson('/forgot-password', ['email' => $user->email]);

    $response->assertStatus(200)
        ->assertJson(['status' => trans(Password::RESET_LINK_SENT)]);

    Notification::assertSentTo($user, ResetPassword::class);
});


test('password can be reset with valid token', function () {
    $this->withoutMiddleware();
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user) {
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200);

        return true;
    });
});
