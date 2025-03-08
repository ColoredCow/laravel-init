<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\User;
test('new users can register', function () {
    $this->withoutMiddleware();
    Event::fake();

    $response = $this->postJson('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201)
             ->assertJson([
                 'message' => 'User registered successfully. Please verify OTP.',
             ]);

    $this->assertAuthenticated();
});

test('registration fails when required fields are missing', function () {
    $this->withoutMiddleware();
    $response = $this->postJson('/register', []);
    dump($response->json());

    $response->assertStatus(422) // 422 = Unprocessable Entity
             ->assertJsonValidationErrors(['name', 'email', 'password']);
});

test('registration fails with invalid email format', function () {
    $this->withoutMiddleware();
    $response = $this->postJson('/register', [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

test('registration fails when email already exists', function () {
    $this->withoutMiddleware();
    User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->postJson('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com', // Duplicate email
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

test('registration fails with weak password', function () {
    $this->withoutMiddleware();
    $response = $this->postJson('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => '123', // Weak password
        'password_confirmation' => '123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

test('registration fails when password confirmation does not match', function () {
    $this->withoutMiddleware();
    $response = $this->postJson('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'differentPassword',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});