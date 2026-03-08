<div class="space-y-1">
    <h3 class="text-sm font-semibold text-foreground">Add Items</h3>
    <p class="text-xs text-muted-foreground">
        Select items to add to your menu structure.
    </p>
    <x-tabs defaultValue="pages" class="pt-3">
        <x-tabs.list class="w-full">
            <x-tabs.trigger value="pages" class="flex-1 gap-1.5 text-xs">
                <x-icon name="file-text" class="size-3.5" />
                Pages
            </x-tabs.trigger>
            <x-tabs.trigger value="custom" class="flex-1 gap-1.5 text-xs">
                <x-icon name="link-2" class="size-3.5" />
                Custom
            </x-tabs.trigger>
        </x-tabs.list>
        <x-tabs.content value="pages" class="mt-3 space-y-3">
            <div class="relative">
                <x-icon name="search" class="absolute left-2.5 top-1/2 size-3.5 -translate-y-1/2 text-muted-foreground" />
                <x-input placeholder="Search pages..." wire:model.live.debounce="query" class="h-8 pl-8 text-xs" />
            </div>
            {{-- TODO --}}
            {{-- <ScrollArea class="h-[180px]"> --}}
            <div class="space-y-0.5 pr-3">
                @forelse ($this->search as $page)
                    <label key={page.id}
                        class="flex cursor-pointer items-center gap-2.5 rounded-md px-2 py-1.5 text-sm transition-colors hover:bg-accent">
                        <x-checkbox :value="$page->id" wire:model="selectedItems" />
                        <span class="flex-1 text-xs">{{ $page->title }}</span>
                        <span class="text-[10px] text-muted-foreground">{{ $page->link() }}</span>
                    </label>
                @empty
                    <p class="py-4 text-center text-xs text-muted-foreground">No pages found.</p>
                @endforelse
            </div>
            {{-- </ScrollArea> --}}
            <x-button size="sm" variant="secondary" class="w-full h-8 text-xs gap-1.5" wire:click="addSelectedItems">
                <x-icon name="plus" class="size-3.5" />
                Add to Menu
            </x-button>
        </x-tabs.content>
        <x-tabs.content value="custom" class="mt-3 space-y-3" x-data="{ url: '', title: '' }">
            <div class="space-y-1.5">
                <x-label htmlFor="custom-label" class="text-xs text-muted-foreground">
                    Link Text
                </x-label>
                <x-input id="custom-label" placeholder="My Custom Link" class="h-8 text-xs" x-model="title" />
            </div>
            <div class="space-y-1.5">
                <x-label htmlFor="custom-url" class="text-xs text-muted-foreground">
                    URL
                </x-label>
                <x-input id="custom-url" placeholder="https://example.com" class="h-8 text-xs" x-model="url" />
            </div>
            <x-button size="sm" class="w-full h-8 text-xs gap-1.5"
                @click="$wire.addItem(title, {
                    'type': 'custom',
                    'url': url
                }); 
                title = ''; 
                url = '';"
                ::disabled="!title || !url">
                <x-icon name="plus" class="size-3.5" />
                Add Custom Link
            </x-button>
        </x-tabs.content>
    </x-tabs>
</div>
