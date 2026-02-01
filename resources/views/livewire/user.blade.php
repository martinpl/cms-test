<?php

use App\Models\User;

new class extends \Livewire\Component {
    public User $user;

    public string $name;

    public string $email;

    public array $roles = [];

    public string $password;

    public function save()
    {
        // TODO: Add validation

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        $user = User::updateOrCreate(['id' => $this->user->id ?? null], $data);
        $user->setMeta('roles', $this->roles);

        if ($user->wasRecentlyCreated) {
            session()->flash('notice', 'New user created.');
            $this->redirectRoute('user', $user->id);
        }

        session()->flash('notice', 'Profile updated.');
    }
}; ?>

<x-slot:title>
    {{ $user ? 'Edit User ' . $user->name : 'Add User' }}
</x-slot>

<x-field.group tag="form" wire:submit="save">
    <x-dashboard-notice />
    <x-field.set>
        <x-field.group>
            <x-field orientation="horizontal">
                <x-field.label class="w-52 shrink-0">
                    Name
                </x-field.label>
                <x-input :value="$user?->name" wire:model.fill="name" required />
            </x-field>
        </x-field.group>
        <x-field.group>
            <x-field orientation="horizontal">
                <x-field.label class="w-52 shrink-0">
                    Email
                </x-field.label>
                <x-input :value="$user?->email" wire:model.fill="email" required />
            </x-field>
        </x-field.group>
        <x-field.group>
            <x-field orientation="horizontal">
                <x-field.label class="w-52 shrink-0">
                    Role
                </x-field.label>
                {{-- TODO: Support select multiple --}}
                <x-native-select wire:model.fill="roles.0">
                    @foreach (app(App\Role::class)->list as $role)
                        <x-native-select.option value="{{ $role['name'] }}" :selected="in_array($role['name'], $user?->meta('roles') ?? [])">{{ $role['title'] }}</x-native-select.option>
                    @endforeach
                </x-native-select>
            </x-field>
        </x-field.group>
        <x-field.group>
            <x-field orientation="horizontal">
                <x-field.label class="w-52 shrink-0">
                    Password
                </x-field.label>
                <x-input type="password" wire:model.fill="password" :required="$user ? null : 'required'" />
            </x-field>
        </x-field.group>
    </x-field.set>
    <x-field orientation="horizontal">
        <x-button type="submit">
            {{ $user ? 'Update User' : 'Add User' }}
        </x-button>
    </x-field>
</x-field.group>
