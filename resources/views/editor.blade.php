@php
    $postType = app(App\PostType::class)->find($postType);
    if (!$postType) {
        abort(404);
    }

    if (request()->isMethod('post')) {
        App\Models\Post::updateOrCreate(
            ['id' => $id],
            [
                'title' => request()->input('title'),
                'content' => request()->input('content'),
                'status' => 'publish',
                'type' => $postType['name'],
                'user_id' => request()->user()->id,
            ]
        );
    }

    $post = $id ? App\Models\Post::find($id) : null;
@endphp

<x-layouts.app :title="__('Editor')">
    <form method="POST">
        @csrf
        <input name="title" type="text" placeholder="Title" value="{{ $post?->title }}"><br>
        <textarea name="content" placeholder="Content">{{ $post?->content }}</textarea>
        <br>
        <button>
            Send
        </button>
    </form>
</x-layouts.app>

