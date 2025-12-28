{{-- TODO: Should not be default layout? --}}
<x-dashboard :title="$title ?? null" :action="$action ?? null">
    {{ $slot }}
</x-dashboard>
