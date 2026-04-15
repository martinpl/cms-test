<x-sidebar.separator class="mx-0" />
<x-sidebar.group class="py-0">
    <x-collapsible class="group/collapsible">
        <x-collapsible.trigger>
            <x-sidebar.group-label
                class="group/label text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground w-full text-sm">
                {{ $title }}
                <x-icon name="chevron-right" class="ml-auto transition-transform group-data-[state=open]/collapsible:rotate-90" />
            </x-sidebar.group-label>
        </x-collapsible.trigger>
        <x-collapsible.content>
            {{-- TODO: HtmlString --}}
            {!! $callback !!}
        </x-collapsible.content>
    </x-collapsible>
</x-sidebar.group>
