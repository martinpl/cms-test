{{-- TODO: Move to php + shouldRender --}}
@php
    use App\AdminMenu\AdminMenu;
    use App\PostTypeRegistry;

    if (auth()->check()) {
        $siteTitle = get_option('site_title', 'Site title');
        $isDashboard = request()->route()->getPrefix() == 'dashboard';
        app('menu.admin-bar')->add(
            AdminMenu::make($siteTitle)
                ->link(fn() => $isDashboard ? route('home') : route('dashboard'))
                ->order(-1)
                ->icon($isDashboard ? 'house' : 'gauge'),
        );

        if (request()->route()->getPrefix() != 'dashboard') {
            app('menu.admin-bar')->add(AdminMenu::make(__('Dashboard'))->parent($siteTitle)->link(fn() => route('dashboard')));
        } else {
            app('menu.admin-bar')->add(AdminMenu::make(__('Home'))->parent($siteTitle)->link(fn() => route('home')));
        }

        app('menu.admin-bar')->add(AdminMenu::make(__('Plugins'))->parent($siteTitle)->link(fn() => route('plugins')));

        app('menu.admin-bar')->add(AdminMenu::make(__('Themes'))->parent($siteTitle)->link(fn() => route('themes')));

        app('menu.admin-bar')->add(
            AdminMenu::make(__('New'))
                ->order(-1)
                ->icon('plus'),
        );

        foreach (app(PostTypeRegistry::class)->list as $postType) {
            app('menu.admin-bar')->add(
                AdminMenu::make($postType['title'])
                    ->parent(__('New'))
                    ->link(fn() => route('editor', $postType['name']))
                    ->icon($postType['icon']),
            );
        }

        app('menu.admin-bar')->add(AdminMenu::make(auth()->user()->name)->group('User'));

        app('menu.admin-bar')->add(
            AdminMenu::make(__('Settings'))
                ->parent(auth()->user()->name)
                ->link(fn() => route('settings.profile')),
        );

        app('menu.admin-bar')->add(
            AdminMenu::make(__('Log Out'))
                ->parent(auth()->user()->name)
                ->link(fn() => route('logout')),
        );

        $list = collect(app('menu.admin-bar')->list)->sortBy('order');
    }
@endphp

@if (auth()->check())
    <x-menubar class="w-full justify-between">
        @foreach ($list->groupBy('group') as $group)
            <div class="flex">
                @foreach ($group->filter(fn($item) => !$item->parent) as $item)
                    <x-menubar.menu>
                        @php($children = $list->filter(fn($children) => $children->parent == $item->title))
                        <x-menubar.trigger :href="$item->link" class="gap-2">
                            <x-icon :name="$item->icon" class="h-4 w-4" stroke-width="1.5" />
                            {{ $item->title }}
                        </x-menubar.trigger>
                        @if ($children->isNotEmpty())
                            <x-menubar.content>
                                @if ($children)
                                    @foreach ($children as $child)
                                        <x-menubar.item :href="$child->link">
                                            <x-icon :name="$child->icon" class="h-4 w-4" stroke-width="1.5" />
                                            {{ $child->title }}
                                        </x-menubar.item>
                                    @endforeach
                                @endif
                            </x-menubar.content>
                        @endif
                    </x-menubar.menu>
                @endforeach
            </div>
        @endforeach
    </x-menubar>
@endif
