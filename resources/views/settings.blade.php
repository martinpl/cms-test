<x-dashboard :title="__('Site settings')">
    <x-field.set>
        <livewire:settings-form fields="App\Schema\Settings::fields" />
    </x-field.set>
</x-dashboard>
