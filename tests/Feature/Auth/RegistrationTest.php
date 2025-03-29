<?php

use Illuminate\Support\Facades\Session;

test('new users can register', function () {
    $this->withoutMiddleware();
    Session::start();
    $csrfToken = csrf_token();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test1@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        '_token' => $csrfToken,
    ]);

    $this->assertAuthenticated();
    $response->assertNoContent();
});
