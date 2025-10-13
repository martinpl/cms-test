@php
    $postType = app(App\PostType::class)->find($postType);
    if (!$postType) {
        abort(404);
    }
    
    $posts = App\Models\Post::where('type', $postType)
        ->latest()
        ->paginate(10);
@endphp

<x-layouts.app :title="$postType['plural']">
    <h2>
        {{ $postType['plural'] }}
    </h2>
    <a href="{{ route('editor', $postType['name']) }}">
        Add
    </a>
    @foreach ($posts as $post)
        <div>
            <a href="{{ route('editor', [$postType['name'], $post->id]) }}">
                {{ $post->title }}
            </a>
            @if ($post->link())
                <a href="{{ $post->link() }}">Preview</a>
            @endif
        </div>
        <hr>
    @endforeach
    {{ $posts->links() }} 
</x-layouts.app>
