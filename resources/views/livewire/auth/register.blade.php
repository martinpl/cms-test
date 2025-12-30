<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.auth')] class extends Livewire\Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <x-field tag="label">
            <x-field.label tag="div">
                {{ __('Name') }}
            </x-field.label>
            <x-input wire:model="name" type="text" required autofocus autocomplete="name" :placeholder="__('Full name')" />
            @error('name')
                <x-field.error>{{ $message }}</x-field.error>
            @enderror
        </x-field>

        <!-- Email Address -->
        <x-field tag="label">
            <x-field.label tag="div">
                {{ __('Email address') }}
            </x-field.label>
            <x-input wire:model="email" type="email" required autocomplete="email" placeholder="email@example.com" />
            @error('email')
                <x-field.error>{{ $message }}</x-field.error>
            @enderror
        </x-field>

        <!-- Password -->
        <x-field tag="label">
            <x-field.label tag="div">
                {{ __('Password') }}
            </x-field.label>
            <x-input wire:model="password" type="password" required autocomplete="new-password" :placeholder="__('Password')" />
            @error('password')
                <x-field.error>{{ $message }}</x-field.error>
            @enderror
        </x-field>

        <!-- Confirm Password -->
        <x-field tag="label">
            <x-field.label tag="div">
                {{ __('Confirm password') }}
            </x-field.label>
            <x-input wire:model="password_confirmation" type="password" required autocomplete="new-password" :placeholder="__('Confirm password')" />
        </x-field>

        <div class="flex items-center justify-end">
            <x-button type="submit" class="w-full">
                {{ __('Create account') }}
            </x-button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Already have an account?') }}</span>
        <x-button :href="route('login')" variant="link" class="p-0" wire:navigate>{{ __('Log in') }}</x-button>
    </div>
</div>
