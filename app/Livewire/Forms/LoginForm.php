<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    // Change the property from email to username or name
    #[Validate('required|string')]
    public string $name = ''; // Change this to $name if using name instead

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Change 'email' to 'username' or 'name'
        if (!Auth::attempt([$this->usernameFieldName() => $this->name, 'password' => $this->password, 'inactive' => false], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'name' => trans('auth.failed'), // Change 'email' to 'username' or 'name'
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    // Update the method name to indicate the field name for username or name
    protected function usernameFieldName(): string
    {
        // Use 'username' or 'name' depending on your implementation
        return 'name';
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'name' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        // Use 'username' or 'name' depending on your implementation
        return Str::transliterate(Str::lower($this->name) . '|' . request()->ip());
    }
}
