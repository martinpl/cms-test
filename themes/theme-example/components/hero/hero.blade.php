<!--
    Name: Hero
-->

@use(App\PostTypes\Attachment)
{{-- TODO: should we return context as object? --}}
{{-- TODO: return defaults? --}}
@props([
    'title' => '',
    'image' => null,
    'repeater' => []
])

<section>
    Title: {{ $title }}
    Image: 
    @if ($image)
        <img src="{{ Storage::url(Attachment::find($image)->content) }}" style="height: 100px">
    @endif
    @dump($repeater)
</section>

