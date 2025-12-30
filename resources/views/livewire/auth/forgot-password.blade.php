<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.auth')] class extends Livewire\Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <x-field tag="label">
            <x-field.label tag="div">
                {{ __('Email Address') }}
            </x-field.label>
            <x-input wire:model="email" type="email" required autofocus placeholder="email@example.com" />
            @error('email')
                <x-field.error>{{ $message }}</x-field.error>
            @enderror
        </x-field>

        <x-button type="submit" class="w-full">{{ __('Email password reset link') }}</x-button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('Or, return to') }}</span>
        <x-button :href="route('login')" variant="link" class="p-0" wire:navigate>{{ __('log in') }}</x-button>
    </div>
</div>
