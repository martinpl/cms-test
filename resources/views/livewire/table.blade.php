@php
    $attributes = new Illuminate\View\ComponentAttributeBag();
    $items = is_array($items = $this->items()) ? collect($items) : $items;
    $columns = $this->columns();
@endphp

<div {{ $attributes->twMerge(['relative flex flex-col gap-4', $class]) }}>
    <div class="flex justify-between">
        @if ($this->views)
            <x-tabs.list
                class="**:data-[slot=badge]:bg-muted-foreground/30 hidden **:data-[slot=badge]:size-5 **:data-[slot=badge]:rounded-full **:data-[slot=badge]:px-1 @4xl/main:flex">
                @foreach ($this->views as $key => $viewName)
                    {{-- @if (($loop->first && isset($this->counts[$key])) || $this->view == $key || $this->counts[$key] ?? true) --}}
                    @php($isSelected = $this->view == $key)
                    @php($hasItems = $this->counts[$key] ?? true)
                    {{-- @if (($loop->first && isset($this->counts[$key])) || $isSelected || $this->counts[$key] ?? true) --}}
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
                    @foreach ($columns as $column)
                        <x-table.head>
                            {{ $column }}
                        </x-table.head>
                    @endforeach
                </x-table.row>
            </x-table.header>
            <x-table.body class="**:data-[slot=table-cell]:first:w-8">
                @foreach ($items as $item)
                    <x-table.row class="group relative z-0 data-[dragging=true]:z-10 data-[dragging=true]:opacity-80">
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
                @if ($items->isEmpty())
                    <x-table.row>
                        <x-table.cell colspan="{{ count($columns) }}" class="h-24 text-center">
                            No results.
                        </x-table.cell>
                    </x-table.row>
                @endif
            </x-table.body>
        </x-table>
    </div>
    {{-- TODO: Add support for non db pagination --}}
    @if (method_exists($items, 'links'))
        {{ $items->links() }}
    @endif
</div>
