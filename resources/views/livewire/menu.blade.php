<?php

use App\PostTypes\Page;
use App\Taxonomies\Menu;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

new class extends Livewire\Component
{
    #[Url]
    public $selected = '';

    public $title;

    public $items = [];

    public $locations = [];

    public $query = '';

    public $selectedItems = [];

    public function mount()
    {
        $items = $this->menu
            ?->posts()
            ->orderBy('order') // TODO: Should be default
            ->get()
            ->map(
                fn ($item, $index) => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'order' => $index,
                    'parent' => $item->parent_id,
                    'meta' => $item->meta(),
                ],
            );

        $this->items = collect($items);
    }

    public function updatedSelected()
    {
        $this->mount();
    }

    #[Computed]
    public function menu()
    {
        return $this->selected ? Menu::find($this->selected) : null;
    }

    public function save()
    {
        $menu = Menu::updateOrCreate(
            ['id' => $this->selected],
            [
                'title' => $this->title,
                'type' => 'menu',
            ],
        );

        if ($menu->wasRecentlyCreated) {
            $this->selected = $menu->id;
        }

        $this->menu
            ?->posts()
            ->whereNotIn('posts.id', $this->items->pluck('id'))
            ->delete();

        foreach ($this->items as $index => $item) {
            $menuItem = App\PostTypes\MenuItem::updateOrCreate(
                ['id' => $item['id']],
                [
                    'title' => $item['title'],
                    'parent_id' => $item['parent'],
                    'order' => $item['order'],
                    'status' => 'publish', // TODO: Should not be needed
                ],
            );

            if ($menuItem->wasRecentlyCreated) {
                $menuItem->terms('menu')->attach($this->menu);
            }

            foreach ($item['meta'] as $key => $value) {
                $menuItem->setMeta($key, $value);
            }
        }

        // TODO: Link meta with theme
        $menu->setMeta('locations', $this->locations);

        $this->mount(); // TODO

        session()->flash('notice', 'Menu update.');
    }

    public function addItem($title, $meta)
    {
        $this->items->push([
            'id' => 'TBD'.rand(),
            'title' => $title,
            'order' => count($this->items),
            'parent' => null,
            'meta' => $meta,
        ]);
    }

    public function removeItem($index)
    {
        $this->items->splice($index, 1);
    }

    #[Computed]
    public function search()
    {
        // TODO: Support multiple posts types
        return Page::where('title', 'like', "%{$this->query}%")
            ->limit(10)
            ->get();
    }

    public function addSelectedItems()
    {
        foreach ($this->selectedItems as $id) {
            $this->addItem(Page::find($id)->title, [
                'type' => 'post_type',
                'post_type_id' => $id,
            ]);
        }

        $this->reset('selectedItems');
    }

    public function deleteMenu()
    {
        $this->menu->posts()->delete();
        $this->menu->delete();
        $this->reset(['selected', 'title']);
        $this->items = collect();
    }
}; ?>

<x-slot:hide-header></x-slot:hide-header>
<x-slot:container class="p-0 md:p-0"></x-slot>

<div>
    <div class="fixed bottom-5 left-5" x-init="setTimeout(() => $el.hidden = true, 5000)">
        <x-dashboard-notice />
    </div>
    <x-dashboard-header title="Menus">
        <div class="mx-auto flex h-14 max-w-6xl items-center gap-4 px-4">
            <div class="flex items-center gap-2">
                <x-native-select wire:model.live="selected" class="h-8 w-[180px] text-xs">
                    <x-native-select.option value="">— New —</x-native-select.option>
                    @foreach (Menu::all() as $menu)
                        <x-native-select.option value="{{ $menu->id }}">{{ $menu->title }}</x-native-select.option>
                    @endforeach
                </x-native-select>
            </div>
        </div>
        <div>
            {{-- {isDirty && ( --}}
            {{-- <x-badge variant="secondary" class="text-[10px] font-normal">
                        Unsaved changes
                    </x-badge> --}}
            {{-- )} --}}
            {{-- TODO: disabled state --}}
            <x-button size="xs" wire:click="save">
                {{ $this->menu ? 'Save' : 'Publish' }}
            </x-button>
    </x-dashboard-header>
    <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr]">
        <aside class="border-r">
            <div class="p-4">
                <x-menu-add-items />
            </div>
            <x-sidebar.separator class="mx-0" />
            <x-sidebar.group class="py-0">
                <x-sidebar.group-label class="group/label text-sidebar-foreground w-full text-sm">
                    Display Location
                </x-sidebar.group-label>
                <x-sidebar.group-content>
                    <x-sidebar.menu>
                        @foreach (app(App\MenuRegistry::class)->list as $key => $menu)
                            <x-sidebar.menu-item wire:key="{{ rand() }}">
                                <x-sidebar.menu-button tag="label">
                                    <x-checkbox wire:model.fill="locations" value="{{ $key }}" :checked="in_array($key, $this->menu?->meta('locations', []) ?? [])" />
                                    {{ $menu }}
                                </x-sidebar.menu-button>
                            </x-sidebar.menu-item>
                        @endforeach
                    </x-sidebar.menu>
                </x-sidebar.group-content>
            </x-sidebar.group>
            <div class="border-t border-border px-4 py-3 space-y-3">
                <div class="space-y-1.5">
                    <x-label htmlFor="menu-name" class="text-xs text-muted-foreground">
                        Menu Name
                    </x-label>
                    <x-input id="menu-name" wire:model.fill="title" :value="$this->menu?->title" class="h-8 text-xs" />
                </div>
                @if ($this->menu)
                    <x-button variant="outline" size="sm"
                        class="w-full h-8 text-xs text-destructive hover:text-destructive hover:bg-destructive/10" wire:click="deleteMenu"
                        wire:confirm="Are you sure you want to delete this menu?">
                        Delete Menu
                    </x-button>
                @endif
            </div>
        </aside>
        <main>
            <div class="flex items-center justify-between border-b border-border px-4 py-3">
                <div class="flex items-center gap-2.5">
                    <x-icon name="menu" class="size-4 text-muted-foreground" />
                    <h2 class="text-sm font-semibold text-foreground">
                        Structure
                    </h2>
                    <x-badge variant="secondary" class="text-[10px]">
                        {{ $this->items->count() }}
                        {{ str('item')->plural($this->items->count()) }}
                    </x-badge>
                </div>
                {{-- TODO --}}
                {{-- <div class="flex items-center gap-1">
                    <x-button variant="ghost" size="sm" class="h-7 gap-1.5 text-[11px] text-muted-foreground">
                        Expand All
                    </x-button>
                    <x-button variant="ghost" size="sm" class="h-7 gap-1.5 text-[11px] text-muted-foreground">
                        Collapse All
                    </x-button>
                </div> --}}
            </div>
            <div class="p-4">
                @if ($this->items->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="flex size-12 items-center justify-center rounded-full bg-muted">
                            <x-icon name="menu" class="size-5 text-muted-foreground" />
                        </div>
                        <p class="mt-3 text-sm font-medium text-foreground">
                            No menu items yet
                        </p>
                        <p class="mt-1 text-xs text-muted-foreground">
                            Add items from the panel on the left to build your menu.
                        </p>
                    </div>
                @endif
                <x-menu-item-card :items="$this->items" :id="null" />
            </div>
        </main>
    </div>
</div>

<script>
    this.$js.sort = (itemId, parent) => {
        this.items.forEach((item, index) => {
            const el = this.$el.querySelector(`[x-sort\\:item="${item.id}"]`);
            const elIndex = [...el.parentElement.children].indexOf(el);
            item.order = elIndex;
            if (item.id == itemId) {
                item.parent = parent
            }
        })
    }
</script>
