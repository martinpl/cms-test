<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;

new class extends Livewire\Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="mt-10 space-y-6">
    <div class="grid gap-1.5 mb-5">
        <x-card.title>{{ __('Delete account') }}</x-card.title>
        <x-card.description>{{ __('Delete your account and all of its resources') }}</x-card.description>
    </div>

    <x-dialog name="confirm-user-deletion">
        <x-dialog.trigger>
            <x-button variant="destructive">
                {{ __('Delete account') }}
            </x-button>
        </x-dialog.trigger>
        <x-dialog.content class="max-w-lg">
            <form method="POST" wire:submit="deleteUser" class="space-y-6">
                <div class="grid gap-2">
                    <x-card.title>{{ __('Are you sure you want to delete your account?') }}</x-card.title>
                    <x-card.description>
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </x-card.description>
                </div>

                <x-field tag="label">
                    <x-field.label tag="div">
                        {{ __('Password') }}
                    </x-field.label>
                    <x-input wire:model="password" type="password" />
                    @error('password')
                        <x-field.error>{{ $message }}</x-field.error>
                    @enderror
                </x-field>

                <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                    <x-dialog.close>
                        <x-button variant="secondary">{{ __('Cancel') }}</x-button>
                    </x-dialog.close>

                    <x-button variant="destructive" type="submit">{{ __('Delete account') }}</x-button>
                </div>
            </form>
        </x-dialog.content>
    </x-dialog>
</section>
