<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException as ValidationEx;

class NewPasswordController extends Controller
{
    protected PasswordBroker $passwordBroker;
    protected Hasher $hasher;
    protected Str $strHelper;

    public function __construct(PasswordBroker $passwordBroker, Hasher $hasher, Str $strHelper)
    {
        $this->passwordBroker = $passwordBroker;
        $this->hasher = $hasher;
        $this->strHelper = $strHelper;
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
        ]);

        // Attempt to reset the user's password
        $status = $this->passwordBroker->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => $this->hasher->make($request->string('password')),
                    'remember_token' => $this->strHelper->random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != PasswordBroker::PASSWORD_RESET) {
            throw new ValidationEx(__('email'), [__($status)]);
        }

        return response()->json(['status' => __($status)]);
    }
}
