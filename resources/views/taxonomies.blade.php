@php
    $taxonomy = App\Facades\Taxonomy::find($taxonomy);
    abort_if(!$taxonomy, 404);
    abort_if(!$taxonomy['public'], 404);

    $postType = App\Facades\PostType::find($postType);
    abort_if(!$postType, 404);
@endphp

<x-dashboard :title="$taxonomy['label']">
    <livewire:dynamic-component :is="$taxonomy['editor']" :$taxonomy :$postType :id="request()->route('id')" />
</x-dashboard>
