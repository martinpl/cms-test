<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

new class extends \Livewire\Component
{
    use App\Livewire\Table;

    public $taxonomyType;

    public $postType;

    public $id;

    public $editor = [
        'title' => '',
        'description' => '',
        'parent_id' => null,
    ];

    public function mount($taxonomyType, $postType)
    {
        $this->taxonomyType = app(App\TaxonomyType::class)->find($taxonomyType);
        abort_if(! $this->taxonomyType, 404);

        $postType = app(App\PostTypeRegistry::class)->find($postType);
        abort_if(! $postType, 404);
    }

    public function save()
    {
        App\Models\Taxonomy::updateOrCreate(
            ['id' => $this->id],
            [
                'title' => $this->editor['title'],
                'description' => $this->editor['description'],
                'type' => $this->taxonomyType['name'],
                'parent_id' => $this->editor['parent_id'],
            ],
        );
    }

    protected function columns()
    {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'slug' => 'Slug',
            'count' => 'Count',
        ];
    }

    protected function items()
    {
        return App\Models\Taxonomy::where('type', $this->taxonomyType)
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->paginate(10);
    }

    protected function columnName($term)
    {
        $title = Blade::render(
            <<<'BLADE'
                <x-button
                    :href="route('taxonomies', [$taxonomyType['name'], $postType, $term->id])"
                    variant="link"
                    class="text-foreground w-fit p-0 h-auto"
                >
                    {{ $term->title }}
                </x-button>
                {{ $actions }}
            BLADE
            ,
            [
                'term' => $term,
                'taxonomyType' => $this->taxonomyType,
                'postType' => $this->postType,
                'actions' => $this->actions($term),
            ],
        );

        // TODO: Direct return give error from ass: "Cannot use "::class" on int" / file parsing issue?
        $title = new HtmlString($title);

        return $title;
    }

    private function actions($term)
    {
        $actions = [
            'edit' => '<a href="'.route('taxonomies', [$this->taxonomyType['name'], $this->postType, $term->id]).'">Edit</a>',
            'delete' => '<button wire:click="destroy('.$term->id.')" class="text-destructive/80" wire:confirm="Are you sure you want to delete this term?">Delete</button>',
        ];

        return $this->rowActions($actions);
    }

    protected function columnSlug($term)
    {
        return $term->name;
    }

    protected function columnCount($term)
    {
        return $term->posts()->where('type', $this->postType)->count();
    }

    public function destroy($termId)
    {
        App\Models\Taxonomy::destroy($termId);
    }

    public function render()
    {
        $term = $this->id ? App\Models\Taxonomy::find($this->id) : null;
        if ($this->id && ! $term) {
            abort(404);
        }

        return $this->view([
            'term' => $term,
            'parents' => $this->taxonomyType['hierarchical'] ? App\Models\Taxonomy::where('type', $this->taxonomyType['name'])->where('id', '!=', $this->id)->get() : collect(),
        ]);
    }
}; ?>

<x-slot:title>
    {{ $taxonomyType['plural'] }}
</x-slot>

<div class="flex gap-11">
    <form wire:submit="save" class="space-y-6 flex-4/12">
        <x-field.group>
            <x-field.set>
                <x-field tag="label">
                    <x-field.label tag="div">
                        Name
                    </x-field.label>
                    <x-input type="text" placeholder="Title" value="{{ $term?->title }}" wire:model.fill="editor.title" />
                </x-field>
                @if ($this->taxonomyType['hierarchical'])
                    <x-field tag="label">
                        <x-field.label tag="div">
                            Parent Category
                        </x-field.label>
                        <x-native-select wire:model.fill="editor.parent_id">
                            <x-native-select.option value="">None</x-native-select.option>
                            @foreach ($parents as $parent)
                                <x-native-select.option value="{{ $parent->id }}"
                                    :selected="$term?->parent_id == $parent->id">{{ $parent->title }}</x-native-select.option>
                            @endforeach
                        </x-native-select>
                    </x-field>
                @endif
                <x-field.group>
                    <x-field.set>
                        <x-field.group>
                            <x-field tag="label">
                                <x-field.label tag="div">
                                    Description
                                </x-field.label>
                                <x-textarea class="resize-none" wire:model.fill="editor.description">{{ $term?->description }}</x-textarea>
                            </x-field>
                        </x-field.group>
                    </x-field.set>
                </x-field.group>
            </x-field.set>
            <x-field orientation="horizontal">
                <x-button>
                    {{ $this->id ? 'Edit' : 'Add' }}
                </x-button>
            </x-field>
        </x-field.group>
    </form>
    {{ $this->table(search: true, class: 'flex-8/12') }}
</div>
