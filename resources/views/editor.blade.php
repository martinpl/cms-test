@php
    $postType = app(App\PostTypeRegistry::class)->find($postType);
    abort_if(!$postType, 404);
@endphp

<x-dashboard :title="__('Editor')" class="p-0 md:p-0">
    <livewire:dynamic-component :is="$postType['editor']" :id="request()->route('id')" :postType="request()->route('postType')" />
</x-dashboard>
