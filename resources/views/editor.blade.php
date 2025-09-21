@php
    $postType = app(App\PostType::class)->find($postType);
    if (!$postType) {
        abort(404);
    }

    if (request()->isMethod('post')) {
        $post = App\Models\Post::updateOrCreate(
            ['id' => $id],
            [
                'title' => request()->input('title'),
                'content' => request()->input('content'),
                'status' => 'publish',
                'type' => $postType['name'],
                'user_id' => request()->user()->id,
            ]
        );
        $post->terms()->sync(request()->input('taxonomies', []));
    }
    $post = $id ? App\Models\Post::with('terms')->find($id) : null;
@endphp

<x-layouts.app :title="__('Editor')">
    <form method="POST">
        @csrf
        <main>
            <input name="title" type="text" placeholder="Title" value="{{ $post?->title }}"><br>
            <textarea name="content" placeholder="Content">{{ $post?->content }}</textarea>
        </main>
        <aside>
            <button>
                Send
            </button>
            @foreach(app(App\TaxonomyType::class)->findForPostType($postType['name']) as $taxonomy)
                @php
                    $taxonomies = App\Models\Taxonomy::where('type', $taxonomy['name'])
                        ->orderBy('title')
                        ->get();
                    $selectedTaxonomies = $post->terms->where('type', $taxonomy['name'])->pluck('id')->toArray();
                @endphp
                <div>
                    {{ $taxonomy['plural'] }}
                </div>
                @foreach($taxonomies as $taxonomy)
                    <label>
                        <input type="checkbox" 
                            name="taxonomies[]" 
                            value="{{ $taxonomy->id }}"
                            @checked(in_array($taxonomy->id, $selectedTaxonomies))>
                        {{ $taxonomy->title }}
                    </label>
                @endforeach
            @endforeach
        </aside>
    </form>
</x-layouts.app>

