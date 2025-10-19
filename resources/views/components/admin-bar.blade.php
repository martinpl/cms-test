{{-- TODO: Move to php + shouldRender --}}
@php
    use App\AdminMenu\AdminMenu;
    use App\PostTypeRegistry;

    if (auth()->check()) {
        app('menu.admin-bar')->add(AdminMenu::make(get_option('site_title'))
            ->link(fn () => route('home'))
            ->order(-1)
            ->icon('adjustments-vertical'));

        if (request()->route()->getPrefix() != 'dashboard') {
            app('menu.admin-bar')->add(AdminMenu::make(__('Dashboard'))
                ->parent(get_option('site_title'))
                ->link(fn () => route('dashboard')));
        } else {
            app('menu.admin-bar')->add(AdminMenu::make(__('Home'))
                ->parent(get_option('site_title'))
                ->link(fn () => route('home')));
        }

        app('menu.admin-bar')->add(AdminMenu::make(__('Plugins'))
            ->parent(get_option('site_title'))
            ->link(fn () => route('plugins')));

        app('menu.admin-bar')->add(AdminMenu::make(__('Themes'))
            ->parent(get_option('site_title'))
            ->link(fn () => route('themes')));

        app('menu.admin-bar')->add(AdminMenu::make(__('New'))
            ->order(-1)
            ->link(fn () => route('home')));

        foreach (app(PostTypeRegistry::class)->list as $postType) {
            app('menu.admin-bar')->add(AdminMenu::make($postType['title'])
                ->parent(__('New'))
                ->link(fn () => route('editor', $postType['name'])));
        }

        app('menu.admin-bar')->add(AdminMenu::make(auth()->user()->name)
            ->group('User'));

        app('menu.admin-bar')->add(AdminMenu::make(__('Log Out'))
            ->parent(auth()->user()->name)
            ->link(fn () => route('logout')));

        $list = collect(app('menu.admin-bar')->list)->sortBy('order');
    }
@endphp

@if (auth()->check())
    <flux:navbar class="w-full justify-between">
        @foreach ($list->groupBy('group') as $group)
            <div class="flex">
                @foreach ($group->filter(fn($item) => !$item->parent) as $item)
                    @php ($children = $list->filter(fn($children) => $children->parent == $item->title))
                    @if ($children->isEmpty())
                        <flux:navbar.item :href="$item->link">{{ $item->title }}</flux:navbar.item>
                    @else
                        <flux:dropdown>
                            <flux:navbar.item icon:trailing="chevron-down">{{ $item->title }}</flux:navbar.item>
                            <flux:navmenu>
                                @if ($children)
                                    @foreach ($children as $child)
                                        <flux:navmenu.item href="{{ $child->link }}">{{ $child->title }}</flux:navmenu.item>
                                    @endforeach
                                @endif
                            </flux:navmenu>
                        </flux:dropdown>
                    @endif
                @endforeach
            </div>
        @endforeach
    </flux:navbar>
@endif