{{-- TODO: Created after migration to Livewire 4, to remove? since it is proxy of layouts.app --}}
<x-layouts.app :title="$title ?? null">
    {{ $slot }}
</x-layouts.app>
