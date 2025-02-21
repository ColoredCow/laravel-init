<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Cache\RateLimiter as RateLimiterContract;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    protected $auth;
    protected $rateLimiter;
    protected $translator;
    protected $stringHelper;

    /**
     * Constructor to inject dependencies.
     */
    public function __construct(StatefulGuard $auth, RateLimiterContract $rateLimiter, Translator $translator, Str $stringHelper)
    {
        parent::__construct();
        $this->auth = $auth;
        $this->rateLimiter = $rateLimiter;
        $this->translator = $translator;
        $this->stringHelper = $stringHelper;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (!$this->auth->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            $this->rateLimiter->hit($this->throttleKey());

            throw new ValidationException($this->getMessageBag(), [
                'email' => $this->translator->get('auth.failed'),
            ]);
        }

        $this->rateLimiter->clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!$this->rateLimiter->tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = $this->rateLimiter->availableIn($this->throttleKey());

        throw new ValidationException($this->getMessageBag(), [
            'email' => $this->translator->get('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return $this->stringHelper->transliterate($this->stringHelper->lower($this->input('email')) . '|' . $this->ip());
    }
}
