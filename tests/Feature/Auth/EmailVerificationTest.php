<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

test('email verification link can verify user', function () {
    Event::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $signedUrl = URL::signedRoute('verification.verify', [
        'id' => $user->id,
        'hash' => sha1($user->email),
    ]);

    $response = $this->actingAs($user)->get($signedUrl);

    $response->assertRedirect(config('app.frontend_url').'/dashboard?verified=1');
    $this->assertNotNull($user->fresh()->email_verified_at);
    Event::assertDispatched(Verified::class);
});

test('already verified user is redirected', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $signedUrl = URL::signedRoute('verification.verify', [
        'id' => $user->id,
        'hash' => sha1($user->email),
    ]);

    $response = $this->actingAs($user)->get($signedUrl);

    $response->assertRedirect(config('app.frontend_url').'/dashboard?verified=1');
});

test('resend verification email for unverified user', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);
    Session::start();
    $csrfToken = csrf_token();
    $response = $this->actingAs($user)
        ->post(route('verification.send'), ['_token' => $csrfToken]);

    $response->assertJson(['status' => 'verification-link-sent']);
});

test('resend verification email fails for verified user', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    Session::start();
    $csrfToken = csrf_token();
    $response = $this->actingAs($user)
        ->post(route('verification.send'), ['_token' => $csrfToken]);

    $response->assertRedirect('/dashboard');
});
