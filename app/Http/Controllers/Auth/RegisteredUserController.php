<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class RegisteredUserController extends Controller
{
    protected User $user;
    protected Hasher $hasher;
    protected StatefulGuard $auth;

    public function __construct(User $user, Hasher $hasher, StatefulGuard $auth)
    {
        $this->user = $user;
        $this->hasher = $hasher;
        $this->auth = $auth;
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $passwordDefaults = Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . $this->user->getTable()],
            'password' => ['required', 'confirmed', $passwordDefaults],
        ]);

        $user = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $this->hasher->make($request->string('password')),
        ]);

        event(new Registered($user));

        $this->auth->login($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
}
