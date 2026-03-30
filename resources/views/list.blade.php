@php
    $postType = App\Facades\PostType::find($postType);
    abort_if(!$postType, 404);
@endphp

<x-dashboard :title="$postType['plural']">
    <livewire:dynamic-component :is="$postType['list']" :$postType />
</x-dashboard>
