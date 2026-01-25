@php
    $attributes = new Illuminate\View\ComponentAttributeBag();
    $columns = $this->columns();
    $bulkActions = $this->bulkActions();
@endphp

<div {{ $attributes->twMerge(['relative flex flex-col gap-4', $class]) }}>
    <x-dashboard-notice />
    <div class="flex justify-between">
        @if ($this->views)
            <x-tabs.list
                class="**:data-[slot=badge]:bg-muted-foreground/30 hidden **:data-[slot=badge]:size-5 **:data-[slot=badge]:rounded-full **:data-[slot=badge]:px-1 @4xl/main:flex">
                @foreach ($this->views as $key => $viewName)
                    @php($isSelected = $this->view == $key)
                    @php($hasItems = $this->counts[$key] ?? true)
                    @if ($loop->first || $hasItems || $isSelected)
                        {{-- TODO: Add links --}}
                        <x-tabs.trigger href="todo" wire:click.prevent="$set('view', '{{ $key }}')" :active="$this->view == $key">
                            {{ $viewName }}
                            @isset($this->counts[$key])
                                <x-badge variant="secondary">{{ $this->counts[$key] }}</x-badge>
                            @endisset
                        </x-tabs.trigger>
                    @endif
                @endforeach
            </x-tabs.list>
        @endif
        @if ($search)
            <x-input type="search" wire:model.change="search" placeholder="Search..." class="max-w-64 ml-auto" />
        @endif
    </div>
    <div class="overflow-hidden rounded-lg border">
        <x-table>
            <x-table.header class="bg-muted sticky top-0 z-10">
                <x-table.row>
                    @if ($draggable && !$this->items->isEmpty())
                        <x-table.head />
                    @endif
                    @if ($bulkActions && !$this->items->isEmpty())
                        <x-table.head>
                            <div class="flex items-center justify-center">
                                <x-checkbox @click="$wire.selected = $el.checked ? {{ $this->items->pluck('id')->toJson() }} : []"
                                    ::checked="$wire.selected.sort().toString() == {{ $this->items->pluck('id')->toJson() }}.sort().toString()" />
                            </div>
                        </x-table.head>
                    @endif
                    @foreach ($columns as $column)
                        <x-table.head>
                            {{ $column }}
                        </x-table.head>
                    @endforeach
                </x-table.row>
            </x-table.header>
            <x-table.body class="**:data-[slot=table-cell]:first:w-8" :wire:sort="$draggable ? 'order' : null">
                @foreach ($this->items as $item)
                    <x-table.row class="group relative z-0 data-[dragging=true]:z-10 data-[dragging=true]:opacity-80" :wire:key="$item->id"
                        :wire:sort:item="$draggable ? $item->id : null">
                        @if ($draggable)
                            <x-table.cell wire:sort:handle>
                                <x-button variant="ghost" size="icon" class="text-muted-foreground size-7 hover:bg-transparent">
                                    <x-icon name="grip-vertical" class="text-muted-foreground size-3" />
                                </x-button>
                            </x-table.cell>
                        @endif
                        @if ($bulkActions)
                            <x-table.cell class="align-baseline w-10">
                                <div class="mt-0.5 flex items-center justify-center">
                                    <x-checkbox :value="$item->id" wire:model="selected" />
                                </div>
                            </x-table.cell>
                        @endif
                        @foreach ($columns as $key => $fsdfsd)
                            <x-table.cell>
                                @php($method = 'column' . ucfirst($key))
                                @if (method_exists($this, $method))
                                    {{ $this->$method($item) }}
                                @else
                                    {{ $item[$key] }}
                                @endif
                            </x-table.cell>
                        @endforeach
                    </x-table.row>
                @endforeach
                @if ($this->items->isEmpty())
                    <x-table.row>
                        <x-table.cell colspan="{{ count($columns) }}" class="h-24 text-center">
                            No results.
                        </x-table.cell>
                    </x-table.row>
                @endif
            </x-table.body>
        </x-table>
    </div>
    <div class="flex justify-between">
        @if ($bulkActions)
            <div class="flex gap-2">
                <x-native-select wire:ref="test">
                    <x-native-select.option value="">Bulk actions</x-native-select.option>
                    @foreach ($bulkActions as $key => $bulkAction)
                        <x-native-select.option value="{{ $key }}">{{ $bulkAction }}</x-native-select.option>
                    @endforeach
                </x-native-select>
                <x-button variant="outline" wire:click="apply($refs.test.value)">Apply</x-button>
            </div>
        @endif
        {{-- TODO: Add support for non db pagination --}}
        @if (method_exists($this->items, 'links'))
            {{ $this->items->links() }}
        @endif
    </div>
</div>
