<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.auth')] class extends Livewire\Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="mt-4 flex flex-col gap-6">
    <x-card.description class="text-center">
        {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
    </x-card.description>

    @if (session('status') == 'verification-link-sent')
        <div class="text-sm text-center font-medium !dark:text-green-400 !text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <x-button wire:click="sendVerification" class="w-full">
            {{ __('Resend verification email') }}
        </x-button>
        <x-button wire:click="logout" variant="link" class="cursor-pointer p-0 h-auto">
            {{ __('Log out') }}
        </x-button>
    </div>
</div>
