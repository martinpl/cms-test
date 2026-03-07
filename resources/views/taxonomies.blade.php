@php
    $taxonomyType = app(App\TaxonomyType::class)->find($taxonomyType);
    abort_if(!$taxonomyType, 404);

    $postType = app(App\PostTypeRegistry::class)->find($postType);
    abort_if(!$postType, 404);
@endphp

<x-dashboard :title="$taxonomyType['plural']">
    <livewire:dynamic-component :is="$taxonomyType['editor']" :$taxonomyType :$postType :id="request()->route('id')" />
</x-dashboard>
