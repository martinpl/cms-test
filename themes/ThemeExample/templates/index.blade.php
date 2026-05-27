<x-canvas>
    {{ post()->title }}
    {!! post()->content !!}
    <br>
    @dump(post()->terms->pluck('type', 'title')->toArray())
    @dump(post()->terms('tag')->pluck('title')->toArray())
    <x-footer />
</x-canvas>
