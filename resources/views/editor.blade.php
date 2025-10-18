@php($postType = app(App\PostType::class)->find(request()->route('postType')))
<x-layouts.app :title="__('Editor')">
    <livewire:dynamic-component :is="$postType['editor']" :id="request()->route('id')" :postType="request()->route('postType')" />
</x-layouts.app>

