<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as ValidationEx;

class PasswordResetLinkController extends Controller
{
    protected PasswordBroker $passwordBroker;

    public function __construct(PasswordBroker $passwordBroker)
    {
        $this->passwordBroker = $passwordBroker;
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Use the injected PasswordBroker instead of static access
        $status = $this->passwordBroker->sendResetLink(
            $request->only('email')
        );

        if ($status != PasswordBroker::RESET_LINK_SENT) {
            throw new ValidationEx(__('email'), [__($status)]);
        }

        return response()->json(['status' => __($status)]);
    }
}
