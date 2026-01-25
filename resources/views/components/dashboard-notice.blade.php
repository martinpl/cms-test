@if (session('notice'))
    <x-alert>
        <x-icon name="info" />
        <x-alert.title> {{ session('notice') }}</x-alert.title>
    </x-alert>
@endif
