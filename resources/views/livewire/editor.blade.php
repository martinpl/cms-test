<?php

use App\PostTypes\AnyPost;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;

new class extends Livewire\Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $postType;

    public $name = '';

    public $title = '';

    public $content = [];

    public $parent = null;

    public $meta = [];

    public $terms = [];

    public function mount()
    {
        if ($this->post) {
            $this->content = json_decode($this->post->getRawOriginal('content'), true) ?: [];
            $this->meta = $this->post->meta();
        }
    }

    #[Computed]
    public function post()
    {
        return $this->id ? AnyPost::with('terms')->find($this->id) : null;
    }

    public function add($name)
    {
        $this->content[] = [
            'name' => $name,
            'data' => [],
        ];
    }

    public function remove($index)
    {
        array_splice($this->content, $index, 1);
    }

    public function saveDraft()
    {
        $this->save('draft');
    }

    public function savePublish()
    {
        $this->save('publish');
    }

    private function save($status)
    {
        $this->post = AnyPost::updateOrCreate(
            ['id' => $this->id],
            [
                'type' => $this->postType,
                'status' => $status,
                'name' => $this->name,
                'title' => $this->title,
                'user_id' => request()->user()->id,
                'content' => $this->content,
                'parent_id' => $this->parent,
            ],
        );
        $this->post->terms()->sync($this->terms);
        foreach ($this->meta as $key => $value) {
            $this->post->setMeta($key, $value);
        }

        if ($this->post->wasRecentlyCreated) {
            $this->redirectRoute('editor', [
                'postType' => $this->postType,
                'id' => $this->post->id,
            ]);
        }

        $this->name = $this->post->name;
    }

    public function setAsHomePage($id)
    {
        set_option('home_page', $id, true);
    }
}; ?>

<div class="flex gap-6">
    <aside class="flex-2/12">
        Add block:<br>
        @foreach (app(\App\BlockType::class)->list ?? [] as $slug => $block)
            <x-button variant="outline" wire:click="add(`{{ $slug }}`)">
                {{ $block['name'] }}
            </x-button>
        @endforeach
        <div>
            Structure:
            @foreach ($content as $block)
                <div>
                    {{ $block['name'] }}
                    <x-button variant="outline" wire:click="remove({{ $loop->index }})">
                        Remove
                    </x-button>
                </div>
            @endforeach
        </div>
    </aside>
    <main class="flex-8/12" x-data="{ selected: null }">
        <input name="title" type="text" placeholder="Title" wire:model.fill="title" value="{{ $this->post?->title }}"><br>
        @foreach ($content as $block)
            @php
                $class = 'Components\\' . Str::studly($block['name']) . '\Schema';
            @endphp
            {{-- TODO: update only one block or cache another? --}}
            <div class="editor-block" @click="selected = {{ $loop->index }}" wire:key="block-{{ $loop->index }}">
                @if (method_exists($class, 'fields'))
                    <div class="fields" x-show="selected == {{ $loop->index }}" x-cloak @click.away="selected = null; $wire.$refresh();">
                        @foreach ($class::fields() as $field)
                            @php
                                // TODO: validation
                                $field->model("content.{$loop->parent->index}.data");
                                $field->value(fn() => $content[$loop->parent->index]['data'][$field->name] ?? null); // TODO: This should not be callback
                            @endphp
                            {{ $field }}
                        @endforeach
                    </div>
                @endif
                <div class="preview" x-show="selected != {{ $loop->index }}">
                    {!! \App\BlockEditor::resolveComponent($block['name'], $content[$loop->index]['data']) !!}
                </div>
            </div>
        @endforeach
    </main>
    <aside class="flex-2/12">
        @if ($this->post?->status != 'publish')
            <x-button variant="outline" wire:click="saveDraft">
                Save draft
            </x-button>
        @endif
        <x-button wire:click="savePublish">
            @if ($this->post?->status != 'publish')
                Publish
            @else
                Save
            @endif
        </x-button>
        <br>
        @if ($this->post?->link())
            <x-button variant="outline" href="{{ $this->post->link() }}">
                Preview
            </x-button>
            <br>
            <x-button variant="outline" wire:click="setAsHomePage({{ $this->post->id }})">Set as homepage</x-button>
            <br>
        @endif
        <div>
            <x-fields.media title="Thumbnail" wire:model="meta.thumbnail" />
            Excerpt:
            <input type="text" wire:model.fill="meta.excerpt"><br>
            Slug:
            <input type="text" wire:model.fill="name" value="{{ $this->post?->name }}"><br>
            Parent:
            {{-- TODO: add select with search --}}
            <input type="number" wire:model.number.fill="parent" value="{{ $this->post?->parent_id }}"><br>
        </div>
        @foreach (app(App\TaxonomyType::class)->findForPostType($this->postType) as $taxonomy)
            @php
                $taxonomies = App\Models\Taxonomy::where('type', $taxonomy['name'])->orderBy('title')->get();
                $selectedTaxonomies = $this->post?->terms->where('type', $taxonomy['name'])->pluck('id')->toArray() ?? [];
            @endphp
            <div>
                {{ $taxonomy['plural'] }}
            </div>
            @foreach ($taxonomies as $taxonomy)
                <label>
                    <input type="checkbox" value="{{ $taxonomy->id }}" wire:model.fill="terms" @checked(in_array($taxonomy->id, $selectedTaxonomies))>
                    {{ $taxonomy->title }}
                </label>
            @endforeach
        @endforeach
    </aside>
    <style>
        .editor-block {
            padding: 1rem;
            border: 1px dashed black;
            margin-bottom: 1rem;
        }
    </style>
</div>
