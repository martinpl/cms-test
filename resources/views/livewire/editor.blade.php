<?php

use App\PostTypes\AnyPost;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;

new class extends Livewire\Component {
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

<x-sidebar.provider style="min-height: calc(100svh - 85px);">
    <x-sidebar collapsible="none" class="bg-transparent border-r h-svh" style="height: auto">
        <x-sidebar.content class="pt-4">
            <x-tabs defaultValue="inserter">
                <div class="px-4">
                    <x-tabs.list class="w-full h-8">
                        <x-tabs.trigger value="inserter" class="text-xs">
                            <x-icon name="plus" /> Add
                        </x-tabs.trigger>
                        <x-tabs.trigger value="overview" class="text-xs">
                            <x-icon name="list" /> Overview
                        </x-tabs.trigger>
                    </x-tabs.list>
                </div>
                {{-- TODO: Change sides scroll width --}}
                <x-tabs.content value="inserter" class="px-4 pb-4 overflow-auto" style="max-height: calc(100svh - 141px)">
                    <div class="grid grid-cols-3">
                        @foreach (app(\App\BlockType::class)->list ?? [] as $slug => $block)
                            <x-button variant="ghost" wire:click="add(`{{ $slug }}`)"
                                class="text-xs font-normal flex-col h-20 gap-3">
                                <x-icon name="cuboid" class="size-5" stroke-width="1.5" />
                                {{ $block['name'] }}
                            </x-button>
                        @endforeach
                    </div>
                </x-tabs.content>
                <x-tabs.content value="overview" class="px-4 pb-4 overflow-auto" style="max-height: calc(100svh - 141px)">
                    <x-sidebar.menu>
                        @foreach ($content as $block)
                            <x-sidebar.menu-item>
                                <x-sidebar.menu-button href="#" class="group text-sm font-medium justify-between">
                                    <div class="flex items-center gap-2">
                                        <x-icon name="cuboid" class="size-3.5" stroke-width="1.5" />
                                        {{ $block['name'] }}
                                    </div>
                                    <x-button variant="ghost" size="icon" class="w-auto opacity-0 group-hover:opacity-100"
                                        wire:click="remove({{ $loop->index }})">
                                        <x-icon name="eraser" class="size-3.5" stroke-width="1.5" />
                                    </x-button>
                                </x-sidebar.menu-button>
                            </x-sidebar.menu-item>
                        @endforeach
                    </x-sidebar.menu>
                </x-tabs.content>
            </x-tabs>
        </x-sidebar.content>
    </x-sidebar>
    <x-sidebar.inset x-data="{ selected: null }" class="gap-4 p-4 overflow-auto" style="height: calc(100svh - 85px);">
        <x-input name="title" type="text" class="shrink-0" placeholder="Add title" wire:model.fill="title"
            value="{{ $this->post?->title }}" />
        @foreach ($content as $block)
            @php
                $class = 'Components\\' . Str::studly($block['name']) . '\Schema';
            @endphp
            {{-- TODO: update only one block or cache another? --}}
            <div @click="$nextTick(() => { selected = {{ $loop->index }} })" wire:key="block-{{ $loop->index }}"
                :class="selected == {{ $loop->index }} && 'border-t border-b p-4 -mx-4'">
                @if (method_exists($class, 'fields'))
                    <div class="fields" x-show="selected == {{ $loop->index }}" x-cloak
                        @click.outside="selected = null; $wire.$refresh();">
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
    </x-sidebar.inset>
    <x-sidebar collapsible="none" class="bg-transparent sticky top-0 hidden h-svh border-l lg:flex" style="height: calc(100svh - 85px)">
        <x-sidebar.content class="p-4 gap-4">
            <div class="flex flex-wrap justify-end gap-2">
                @if ($this->post?->link())
                    <x-button target="_blank" variant="outline" href="{{ $this->post->link() }}">
                        <x-icon name="square-arrow-out-up-right" />
                    </x-button>
                @endif
                @if ($this->post?->status != 'publish')
                    <x-button variant="ghost" wire:click="saveDraft">
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
            </div>
            @if ($this->post?->link())
                <x-button variant="outline" wire:click="setAsHomePage({{ $this->post->id }})">Set as homepage</x-button>
            @endif
            <x-fields.media title="Thumbnail" wire:model="meta.thumbnail" />
            <div>
                Excerpt:
                <x-textarea wire:model.fill="meta.excerpt"></x-textarea>
            </div>
            <div>
                Slug:
                <x-input type="text" wire:model.fill="name" value="{{ $this->post?->name }}" />
            </div>
            <div>
                Parent:
                {{-- TODO: add combobox with search --}}
                <x-input type="number" wire:model.number.fill="parent" value="{{ $this->post?->parent_id }}" /><br>
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
        </x-sidebar.content>
    </x-sidebar>
</x-sidebar.provider>
