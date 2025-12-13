{{-- TODO: Should not be default layout? --}}
<x-dashboard :title="$title ?? null">
    {{ $slot }}
</x-dashboard>
