<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

new class extends Livewire\Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <x-field tag="label">
                <x-field.label tag="div">
                    {{ __('Current password') }}
                </x-field.label>
                <x-input wire:model="current_password" type="password" required autocomplete="current-password" />
                @error('current_password')
                    <x-field.error>{{ $message }}</x-field.error>
                @enderror
            </x-field>

            <x-field tag="label">
                <x-field.label tag="div">
                    {{ __('New password') }}
                </x-field.label>
                <x-input wire:model="password" type="password" required autocomplete="new-password" />
                @error('password')
                    <x-field.error>{{ $message }}</x-field.error>
                @enderror
            </x-field>

            <x-field tag="label">
                <x-field.label tag="div">
                    {{ __('Confirm Password') }}
                </x-field.label>
                <x-input wire:model="password_confirmation" type="password" required autocomplete="new-password" />
            </x-field>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <x-button type="submit" class="w-full">{{ __('Save') }}</x-button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
