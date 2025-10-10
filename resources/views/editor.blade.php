{{-- TODO: Move to route --}}
<x-layouts.app :title="__('Editor')">
    <livewire:editor :id="request()->route('id')" :postType="request()->route('postType')" />
</x-layouts.app>

