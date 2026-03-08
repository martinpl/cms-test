@props([
    'items' => [],
    'parent' => null,
])

<div @if ($parent) style="margin-left:  40px" @endif x-sort="$js.sort($item, @js($parent))"
    x-sort:group="menus" class="space-y-2">
    @foreach ($items->filter(fn($item) => $item['parent'] == $parent) as $index => $item)
        <div class="space-y-2" wire:key="{{ rand() }}" x-sort:item="{{ $item['id'] }}">
            <x-collapsible>
                <div class="group overflow-hidden rounded-lg border border-border bg-card transition-shadow hover:shadow-sm">
                    <div class="flex items-center gap-2 px-3 py-2.5">
                        <div class="cursor-grab text-muted-foreground hover:text-foreground active:cursor-grabbing"
                            aria-label="Drag to reorder" wire:sort:handle>
                            <x-icon name="grip-vertical" class="size-4" />
                        </div>
                        <x-collapsible.trigger>
                            <button
                                class="flex size-5 items-center justify-center rounded text-muted-foreground hover:bg-accent hover:text-foreground"
                                :aria-label="collapsible ? 'Collapse' : 'Expand'">
                                <x-icon name="chevron-down" class="size-3.5" x-show="collapsible" x-cloak />
                                <x-icon name="chevron-right" class="size-3.5" x-show="!collapsible" />
                            </button>
                        </x-collapsible.trigger>
                        {{-- TODO --}}
                        {{-- <div class="flex items-center gap-2 text-muted-foreground">
                            {getTypeIcon(item.type)}
                        </div> --}}
                        <span class="flex-1 truncate text-sm font-medium text-card-foreground">
                            {{ $item['title'] }}
                        </span>
                        {{-- TODO --}}
                        {{-- <x-badge variant="outline" class="text-[10px] font-normal text-muted-foreground">
                            {getTypeLabel(item.type)}
                        </x-badge> --}}
                        <x-button variant="ghost" size="icon"
                            class="size-7 text-muted-foreground hover:text-destructive opacity-0 group-hover:opacity-100 transition-opacity"
                            aria-label="Delete menu item" wire:click="removeItem({{ $index }})">
                            <x-icon name="trash-2" class="size-3.5" />
                        </x-button>
                    </div>
                    <x-collapsible.content>
                        <div class="border-t border-border bg-muted/30 px-4 py-3">
                            <div class="space-y-3">
                                <div class="space-y-1.5">
                                    <x-label htmlFor="{`label-${item.id}`}" class="text-xs text-muted-foreground">
                                        Navigation Label
                                    </x-label>
                                    <x-input id="{`label-${item.id}`}" wire:model="items.{{ $index }}.title" class="h-8 text-sm"
                                        autoFocus />
                                </div>
                                @if (($item['meta']['type'] ?? '') == 'custom')
                                    <div class="space-y-1.5">
                                        <x-label htmlFor="{`url-${item.id}`}" class="text-xs text-muted-foreground">
                                            URL
                                        </x-label>
                                        <x-input id="{`url-${item.id}`}" wire:model="items.{{ $index }}.meta.url"
                                            class="h-8 text-sm" />
                                    </div>
                                @endif
                                @if ($item['meta']['type'] == 'post_type')
                                    @php($link = App\PostTypes\AnyPost::find($item['meta']['post_type_id'])->link())
                                    <a href="{{ $link }}" target="_blank"
                                        class="flex items-center gap-2 text-xs text-muted-foreground">
                                        <x-icon name="external-link" class="size-3" />
                                        <span class="truncate">
                                            {{ $link }}
                                        </span>
                                    </a>
                                @endif
                                {{-- TODO --}}
                                {{-- <div class="flex flex-wrap items-center gap-1">
                                    <span class="mr-1 text-[10px] font-medium uppercase tracking-wider text-muted-foreground">
                                        Move
                                    </span>
                                    <x-button variant="outline" size="sm" class="h-6 px-2 text-[11px]" disabled={isFirst}>
                                        Up
                                    </x-button>
                                    <x-button variant="outline" size="sm" class="h-6 px-2 text-[11px]" disabled={isLast}>
                                        Down
                                    </x-button>
                                    <div class="mx-1 h-4 w-px bg-border"></div>
                                    <x-button variant="outline" size="sm" class="h-6 px-2 text-[11px]" disabled={!canIndent}>
                                        Indent
                                    </x-button>
                                    <x-button variant="outline" size="sm" class="h-6 px-2 text-[11px]" disabled={!canOutdent}>
                                        Outdent
                                    </x-button>
                                </div> --}}
                            </div>
                        </div>
                    </x-collapsible.content>
                </div>
            </x-collapsible>
            <x-menu-item-card :$items :parent="$item['id']" />
        </div>
    @endforeach
</div>
