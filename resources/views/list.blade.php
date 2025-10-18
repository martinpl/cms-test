@php
    $postType = app(App\PostType::class)->find($postType);
    abort_if(!$postType, 404);
@endphp

<x-layouts.app :title="$postType['plural']">
    <livewire:dynamic-component :is="$postType['list']" :$postType />
</x-layouts.app>
