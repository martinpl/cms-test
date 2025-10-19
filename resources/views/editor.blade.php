@php($postType = app(App\PostTypeRegistry::class)->find(request()->route('postType')))
<x-layouts.app :title="__('Editor')">
    <livewire:dynamic-component :is="$postType['editor']" :id="request()->route('id')" :postType="request()->route('postType')" />
</x-layouts.app>

