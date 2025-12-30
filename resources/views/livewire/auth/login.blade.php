<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

new #[Layout('components.layouts.auth')] class extends Livewire\Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
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
            'email' => __('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <x-field tag="label">
            <x-field.label tag="div">
                {{ __('Email address') }}
            </x-field.label>
            <x-input wire:model="email" type="email" required autofocus autocomplete="email" placeholder="email@example.com" />
            @error('email')
                <x-field.error>{{ $message }}</x-field.error>
            @enderror
        </x-field>

        <!-- Password -->
        <div class="relative">
            <x-field tag="label">
                <x-field.label tag="div">
                    {{ __('Password') }}
                </x-field.label>
                <x-input wire:model="password" type="password" required autocomplete="current-password" :placeholder="__('Password')" />
                @error('password')
                    <x-field.error>{{ $message }}</x-field.error>
                @enderror
            </x-field>

            @if (Route::has('password.request'))
                {{-- TODO: twMerge bug end-0 is stripped --}}
                <x-button :href="route('password.request')" variant="link" class="inline absolute end-0 top-0 text-sm p-0" wire:navigate>
                    {{ __('Forgot your password?') }}
                </x-button>
            @endif
        </div>

        <!-- Remember Me -->
        <x-field tag="label" orientation="horizontal">
            <x-checkbox wire:model="remember" />
            <x-field.label tag="div" class="font-normal">{{ __('Remember me') }}</x-field.label>
        </x-field>

        <div class="flex items-center justify-end">
            <x-button type="submit" class="w-full">{{ __('Log in') }}</x-button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <x-button :href="route('register')" variant="link" class="p-0" wire:navigate>
                {{ __('Sign up') }}
            </x-button>
        </div>
    @endif
</div>
