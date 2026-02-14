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

        session()->flash('notice', 'Page update.');
    }

    public function setAsHomePage($id)
    {
        set_option('home_page', $id, true);
    }
}; ?>

{{-- TODO: using slot is hacky / we should allow to change whole layout --}}
<x-slot:hide-header></x-slot:hide-header>

<div>
    <x-dashboard-header title="Editor">
        <div class="mx-auto max-w-xl w-full">
            {{-- TODO: Filled input styling / twMerge bug --}}
            <x-input name="title" type="text" placeholder="Add title" wire:model.fill="title" value="{{ $this->post?->title }}" />
        </div>
        <div class="flex gap-2">
            @if ($this->post?->link())
                <x-button variant="ghost" size="xs" :href="$this->post->link()" target="_blank">
                    <x-icon name="square-arrow-out-up-right" class="size-3.5" />
                </x-button>
            @endif
            @if ($this->post?->status != 'publish')
                <x-button variant="ghost" size="xs" wire:click="saveDraft">
                    Save draft
                </x-button>
            @endif
            <x-button size="xs" wire:click="savePublish">
                @if ($this->post?->status != 'publish')
                    Publish
                @else
                    Save
                @endif
            </x-button>
        </div>
    </x-dashboard-header>
    <x-sidebar.provider class="min-h-auto" x-data="{ selected: null }">
        <x-sidebar collapsible="none" class="bg-transparent border-r h-auto">
            <x-sidebar.content class="pt-4">
                <x-tabs defaultValue="inserter">
                    <div class="px-4">
                        <x-tabs.list class="w-full h-8" @click.stop="">
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
                                    <x-sidebar.menu-button class="group text-sm font-medium justify-between" ::class="selected == {{ $loop->index }} && 'bg-sidebar-accent text-sidebar-accent-foreground'"
                                        tag="div" @click.stop="selected = {{ $loop->index }}">
                                        <div class="flex items-center gap-2">
                                            <x-icon name="cuboid" class="size-3.5" stroke-width="1.5" />
                                            {{ $block['name'] }}
                                        </div>
                                        <x-button variant="ghost" size="icon" class="w-auto opacity-0 group-hover:opacity-100"
                                            wire:click.stop="remove({{ $loop->index }})">
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
        <x-sidebar.inset class="overflow-auto" style="height: calc(100svh - 85px);">
            @foreach ($content as $block)
                @php $class = 'Components\\' . Str::studly($block['name']) . '\Schema'; @endphp
                {{-- TODO: update only one block or cache another? --}}
                <div @click.stop="selected = {{ $loop->index }}" wire:key="block-{{ $loop->index }}"
                    :class="selected == {{ $loop->index }} &&
                        {{ $loop->index == 0 ? "'border-b'" : "'border-t border-b'" }}">
                    @if (method_exists($class, 'fields') && $class::position() == 'content')
                        <div class="p-4 md:p-6" x-show="selected == {{ $loop->index }}" x-cloak @click.outside="selected = null">
                            <x-fields.fields :fields="$class::fields()" :live="true" model="content.{$loop->parent->index}.data" />
                        </div>
                    @endif
                    <div @if ($class::position() == 'content') x-show="selected != {{ $loop->index }}" @endif>
                        {!! \App\BlockEditor::resolveComponent($block['name'], $this->content[$loop->index]['data']) !!}
                    </div>
                </div>
            @endforeach
        </x-sidebar.inset>
        <x-sidebar collapsible="none" class="bg-transparent sticky top-0 hidden h-svh border-l lg:flex" style="height: calc(100svh - 85px)"
            @click.stop="">
            <x-sidebar.content class="pt-4">
                <x-tabs defaultValue="page" x-init="$watch('selected', (value) => { if (value != null) { tab = 'block' } })">
                    <div class="px-4">
                        <x-tabs.list class="w-full h-8">
                            <x-tabs.trigger value="page" class="text-xs">
                                Page
                            </x-tabs.trigger>
                            <x-tabs.trigger value="block" class="text-xs">
                                Block
                            </x-tabs.trigger>
                        </x-tabs.list>
                    </div>
                    <x-tabs.content value="page" class="pb-4 overflow-auto" style="max-height: calc(100svh - 141px)">
                        <div class="px-4 pb-4 grid gap-4">
                            @if ($this->post?->link())
                                <x-button variant="outline" size="sm" wire:click="setAsHomePage({{ $this->post->id }})">
                                    Set as homepage
                                </x-button>
                            @endif
                            <x-field tag="label">
                                <x-field.label tag="div">
                                    Featured image
                                </x-field.label>
                                <x-fields.media title="Thumbnail" wire:model="meta.thumbnail" />
                            </x-field>
                            <x-field tag="label">
                                <x-field.label tag="div">
                                    Excerpt
                                </x-field.label>
                                <x-textarea wire:model.fill="meta.excerpt"></x-textarea>
                            </x-field>
                            <x-field tag="label">
                                <x-field.label tag="div">
                                    Slug
                                </x-field.label>
                                <x-input type="text" wire:model.fill="name" value="{{ $this->post?->name }}" />
                            </x-field>
                            <x-field tag="label">
                                <x-field.label tag="div">
                                    Parent:
                                </x-field.label>
                                {{-- TODO: add combobox with search --}}
                                <x-input type="number" wire:model.number.fill="parent" value="{{ $this->post?->parent_id }}" />
                            </x-field>
                        </div>
                        @foreach (app(App\TaxonomyType::class)->findForPostType($this->postType) as $taxonomy)
                            @php
                                $taxonomies = App\Models\Taxonomy::where('type', $taxonomy['name'])->orderBy('title')->get();
                                $selectedTaxonomies = $this->post?->terms->where('type', $taxonomy['name'])->pluck('id')->toArray() ?? [];
                            @endphp
                            <x-sidebar.separator class="mx-0" />
                            <x-sidebar.group class="py-0">
                                <x-collapsible class="group/collapsible">
                                    <x-collapsible.trigger>
                                        <x-sidebar.group-label
                                            class="group/label text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground w-full text-sm">
                                            {{ $taxonomy['plural'] }}
                                            <x-icon name="chevron-right"
                                                class="ml-auto transition-transform group-data-[state=open]/collapsible:rotate-90" />
                                        </x-sidebar.group-label>
                                    </x-collapsible.trigger>
                                    <x-collapsible.content>
                                        <x-sidebar.group-content>
                                            <x-sidebar.menu>
                                                @foreach ($taxonomies as $taxonomy)
                                                    <x-sidebar.menu-item>
                                                        <x-sidebar.menu-button tag="label">
                                                            <div class="relative">
                                                                <input type="checkbox"
                                                                    class="appearance-none peer border-sidebar-border checked:border-sidebar-primary checked:bg-sidebar-primary block aspect-square size-4 rounded-xs border"
                                                                    value="{{ $taxonomy->id }}" wire:model.fill="terms"
                                                                    @checked(in_array($taxonomy->id, $selectedTaxonomies))>
                                                                <span
                                                                    class="pointer-events-none hidden peer-checked:grid place-content-center absolute inset-0">
                                                                    <x-icon name="check"
                                                                        class="size-3 text-sidebar-primary-foreground" />
                                                                </span>
                                                            </div>
                                                            {{ $taxonomy->title }}
                                                        </x-sidebar.menu-button>
                                                    </x-sidebar.menu-item>
                                                @endforeach
                                            </x-sidebar.menu>
                                        </x-sidebar.group-content>
                                    </x-collapsible.content>
                                </x-collapsible>
                            </x-sidebar.group>
                        @endforeach
                    </x-tabs.content>
                    <x-tabs.content value="block" class="px-4 pb-4 overflow-auto" style="max-height: calc(100svh - 141px)">
                        @foreach ($content as $block)
                            @php $class = 'Components\\' . Str::studly($block['name']) . '\Schema'; @endphp
                            @if (method_exists($class, 'fields') && $class::position() == 'side')
                                <div x-show="selected == {{ $loop->index }}" x-cloak>
                                    <x-fields.fields :fields="$class::fields()" :live="true" model="content.{$loop->parent->index}.data" />
                                </div>
                            @endif
                        @endforeach
                    </x-tabs.content>
                </x-tabs>
            </x-sidebar.content>
        </x-sidebar>
    </x-sidebar.provider>
    {{-- TODO: twMerge bug fixed class dosn't work with dashboard-notice   --}}
    {{-- TODO: Add animation or switch completely component --}}
    <div class="fixed bottom-5 left-5" x-init="setTimeout(() => $el.hidden = true, 5000)">
        <x-dashboard-notice />
    </div>
</div>
