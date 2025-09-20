<x-layouts.app :title="__('Settings')">
    <flux:heading size="xl">Site Settings</flux:heading>
    <livewire:settings-form fields="App\Schema\Settings::fields" />
</x-layouts.app>
