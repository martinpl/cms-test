@php($postType = app(App\PostTypeRegistry::class)->find(request()->route('postType')))
<x-dashboard :title="__('Editor')">
    <livewire:dynamic-component :is="$postType['editor']" :id="request()->route('id')" :postType="request()->route('postType')" />
</x-dashboard>
