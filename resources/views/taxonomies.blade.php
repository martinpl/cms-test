@php
    $taxonomyType = app(App\TaxonomyType::class)->find($taxonomyType);
    if (!$taxonomyType) {
        abort(404);
    }

    if (request()->isMethod('post')) {
        $post = App\Models\Taxonomy::updateOrCreate(
            ['id' => $id],
            [
                'title' => request()->input('title'),
                'description' => request()->input('description'),
                'type' => $taxonomyType['name'],
                'parent_id' => request()->input('parent_id') ?: null,
            ]
        );
    }
    
    $terms = App\Models\Taxonomy::where('type', $taxonomyType)
        ->paginate(10);
    $term = $id ? App\Models\Taxonomy::find($id) : null;
    $parents = $taxonomyType['hierarchical'] ? App\Models\Taxonomy::where('type', $taxonomyType['name'])
        ->where('id', '!=', $id)
        ->get() : collect();
@endphp

<x-layouts.app :title="$taxonomyType['plural']">
    <form method="POST" class="space-y-6">
        @csrf
        <input name="title" type="text" placeholder="Title" value="{{ $term?->title }}">
        @if($parents->count())
            <select name="parent_id">
                <option value="">No parent</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" @selected($term?->parent_id == $parent->id)>
                        {{ $parent->title }}
                    </option>
                @endforeach
            </select>
        @endif
        <button>
            Send
        </button>
    </form>
    <div>
        <h2>{{ $taxonomyType['plural'] }}</h2>
        @foreach ($terms as $term)
            <div>
                <a href="{{ route('taxonomies', [$taxonomyType['name'], $postType, $term->id]) }}">
                    {{ $term->title }}
                </a>
                <span>
                    {{ $term->posts()->where('type', $postType)->count() }} posts
                </span>
            </div>
        @endforeach
        {{ $terms->links() }}
    </div>
</x-layouts.app>
