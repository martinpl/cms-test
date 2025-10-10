<x-canvas>
    {{ $$postType->title }}
    {!! $$postType->content !!}
    <br>
    @dump($$postType->terms->pluck('type', 'title')->toArray())
    @dump($$postType->terms('tag')->pluck('title')->toArray())
    <x-footer />
</x-canvas>
