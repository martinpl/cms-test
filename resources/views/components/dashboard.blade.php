@props(['title', 'action' => ''])

{{-- TODO: Move out to php, add helper for admin menu --}}
@php
    foreach (app(App\PostTypeRegistry::class)->list as $postType) {
        app('menu.admin')->add(
            \App\AdminMenu\AdminMenu::make($postType['plural'])
                ->link(fn() => route('list', $postType['name']))
                ->current(fn() => request()->routeIs('list') && request()->route('postType') == $postType['name'])
                ->icon($postType['icon']),
        );

        foreach (app(App\TaxonomyType::class)->findForPostType($postType['name']) as $taxonomy) {
            app('menu.admin')->add(
                \App\AdminMenu\AdminMenu::make($taxonomy['title'])
                    ->link(fn() => route('taxonomies', [$taxonomy['name'], $postType['name']]))
                    ->current(
                        fn() => request()->routeIs('taxonomies') &&
                            request()->route('taxonomyType') == $taxonomy['name'] &&
                            request()->route('postType') == $postType['name'],
                    )
                    ->parent($postType['plural']),
            );
        }
    }

    $list = collect(app('menu.admin')->list)->sortBy('order');
@endphp

{{-- TODO: Clear out DOM --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root.dark {
            color-scheme: dark;
        }
    </style>
    <script>
        function applyAppearance(appearance) {
            const applyDark = () => document.documentElement.classList.add('dark')
            const applyLight = () => document.documentElement.classList.remove('dark')

            if (appearance === 'system') {
                const media = window.matchMedia('(prefers-color-scheme: dark)')
                window.localStorage.removeItem('appearance')
                media.matches ? applyDark() : applyLight()
            } else if (appearance === 'dark') {
                applyDark()
            } else if (appearance === 'light') {
                applyLight()
            }
        }
    </script>
</head>

<body>
    <script>
        applyAppearance(window.localStorage.getItem('appearance')?.slice(1, -1) || 'system')
    </script>
    <x-admin-bar />
    <div data-slot="sidebar-wrapper"
        style="--sidebar-width:calc(var(--spacing) * 64);--sidebar-width-icon:3rem;--header-height:calc(var(--spacing) * 12 + 1px)"
        class="group/sidebar-wrapper has-data-[variant=inset]:bg-sidebar min-h-svh w-full flex">
        <div data-slot="sidebar" class="bg-sidebar text-sidebar-foreground flex w-(--sidebar-width) flex-col h-auto border-r">
            <div data-slot="sidebar-header" data-sidebar="header" class="flex flex-col gap-2 p-2 border-b">
                <ul data-slot="sidebar-menu" data-sidebar="menu" class="flex w-full min-w-0 flex-col gap-1">
                    <li data-slot="sidebar-menu-item" data-sidebar="menu-item" class="group/menu-item relative">
                        <a data-slot="sidebar-menu-button" data-sidebar="menu-button" data-size="default" data-active="false"
                            class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left outline-hidden ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 active:bg-sidebar-accent active:text-sidebar-accent-foreground disabled:pointer-events-none disabled:opacity-50 group-has-data-[sidebar=menu-action]/menu-item:pr-8 aria-disabled:pointer-events-none aria-disabled:opacity-50 data-[active=true]:bg-sidebar-accent data-[active=true]:font-medium data-[active=true]:text-sidebar-accent-foreground data-[state=open]:hover:bg-sidebar-accent data-[state=open]:hover:text-sidebar-accent-foreground group-data-[collapsible=icon]:size-8! group-data-[collapsible=icon]:p-2! [&amp;&gt;span:last-child]:truncate [&amp;&gt;svg]:size-4 [&amp;&gt;svg]:shrink-0 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground h-8 text-sm data-[slot=sidebar-menu-button]:!p-1.5"
                            href="{{ route('dashboard') }}" wire:navigate>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="tabler-icon tabler-icon-inner-shadow-top !size-5">
                                <path d="M5.636 5.636a9 9 0 1 0 12.728 12.728a9 9 0 0 0 -12.728 -12.728z"></path>
                                <path d="M16.243 7.757a6 6 0 0 0 -8.486 0"></path>
                            </svg>
                            <span class="text-base font-semibold">Acme Inc.</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div data-slot="sidebar-content" data-sidebar="content"
                class="flex min-h-0 flex-1 flex-col gap-2 overflow-auto group-data-[collapsible=icon]:overflow-hidden">
                @foreach ($list->groupBy('group') as $key => $group)
                    <div data-slot="sidebar-group" data-sidebar="group" class="relative flex w-full min-w-0 flex-col p-2">
                        <div data-slot="sidebar-group-content" data-sidebar="group-content" class="w-full text-sm">
                            @if ($key)
                                <div data-slot="sidebar-group-label" data-sidebar="group-label"
                                    class="text-sidebar-foreground/70 ring-sidebar-ring flex h-8 shrink-0 items-center rounded-md px-2 text-xs font-medium outline-hidden transition-[margin,opacity] duration-200 ease-linear focus-visible:ring-2 [&amp;&gt;svg]:size-4 [&amp;&gt;svg]:shrink-0 group-data-[collapsible=icon]:-mt-8 group-data-[collapsible=icon]:opacity-0">
                                    {{ $key }}
                                </div>
                            @endif
                            <ul data-slot="sidebar-menu" data-sidebar="menu" class="flex w-full min-w-0 flex-col gap-0.5">
                                @foreach ($group->filter(fn($item) => !$item->parent) as $item)
                                    <li data-slot="sidebar-menu-item" data-sidebar="menu-item" class="group/menu-item relative">
                                        <a href="{{ $item->link }}" data-slot="sidebar-menu-button" data-sidebar="menu-button"
                                            data-size="default" data-active="false" @class([
                                                'peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left outline-hidden ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 active:bg-sidebar-accent active:text-sidebar-accent-foreground disabled:pointer-events-none disabled:opacity-50 group-has-data-[sidebar=menu-action]/menu-item:pr-8 aria-disabled:pointer-events-none aria-disabled:opacity-50 data-[active=true]:bg-sidebar-accent data-[active=true]:font-medium data-[active=true]:text-sidebar-accent-foreground data-[state=open]:hover:bg-sidebar-accent data-[state=open]:hover:text-sidebar-accent-foreground group-data-[collapsible=icon]:size-8! group-data-[collapsible=icon]:p-2! [&amp;&gt;span:last-child]:truncate [&amp;&gt;svg]:size-4 [&amp;&gt;svg]:shrink-0 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground h-8 text-sm',
                                                'bg-sidebar-accent' => $item->current,
                                            ]) data-state="closed"
                                            wire:navigate>
                                            <x-icon :name="$item->icon" class="h-4 w-4" stroke-width="1.5" />
                                            <span>{{ $item->title }}</span>
                                        </a>
                                    </li>
                                    @php($children = $list->filter(fn($children) => $children->parent == $item->title))
                                    @if ($children)
                                        <div data-state="open" id="radix-_R_359av5ubsnpf6lb_" data-slot="collapsible-content">
                                            <ul data-slot="sidebar-menu-sub" data-sidebar="menu-sub"
                                                class="border-sidebar-border mx-3.5 flex min-w-0 translate-x-px flex-col gap-1 border-l px-2.5 py-0.5 group-data-[collapsible=icon]:hidden">
                                                @foreach ($children as $child)
                                                    <li data-slot="sidebar-menu-sub-item" data-sidebar="menu-sub-item"
                                                        class="group/menu-sub-item relative">
                                                        <a href="{{ $child->link }}" wire:navigate data-slot="sidebar-menu-sub-button"
                                                            data-sidebar="menu-sub-button" data-size="md" data-active="false"
                                                            @class([
                                                                'text-sidebar-foreground ring-sidebar-ring hover:bg-sidebar-accent hover:text-sidebar-accent-foreground active:bg-sidebar-accent active:text-sidebar-accent-foreground [&amp;&gt;svg]:text-sidebar-accent-foreground flex h-7 min-w-0 -translate-x-px items-center gap-2 overflow-hidden rounded-md px-2 outline-hidden focus-visible:ring-2 disabled:pointer-events-none disabled:opacity-50 aria-disabled:pointer-events-none aria-disabled:opacity-50 [&amp;&gt;span:last-child]:truncate [&amp;&gt;svg]:size-4 [&amp;&gt;svg]:shrink-0 data-[active=true]:bg-sidebar-accent data-[active=true]:text-sidebar-accent-foreground text-sm group-data-[collapsible=icon]:hidden',
                                                                'bg-sidebar-accent' => $child->current,
                                                            ])>
                                                            <span>{{ $child->title }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <main data-slot="sidebar-inset"
            class="bg-background relative flex w-full flex-1 flex-col md:peer-data-[variant=inset]:m-2 md:peer-data-[variant=inset]:ml-0 md:peer-data-[variant=inset]:rounded-xl md:peer-data-[variant=inset]:shadow-sm md:peer-data-[variant=inset]:peer-data-[state=collapsed]:ml-2">
            <header
                class="bg-background/90 sticky top-0 z-10 flex h-(--header-height) shrink-0 items-center gap-2 border-b transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-(--header-height)">
                <div class="flex w-full items-center gap-1 px-4 lg:gap-2 lg:px-6">
                    <h1 class="text-base font-medium">{{ $title }}</h1>
                    {{ $action }}
                </div>
            </header>
            <div class="flex flex-1 flex-col">
                <div {{ $attributes->twMerge('@container/main p-4 md:p-6') }}>
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    @fluxScripts
</body>

</html>
