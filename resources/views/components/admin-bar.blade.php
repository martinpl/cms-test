{{-- TODO: Move to php + shouldRender --}}
@php
    use App\AdminMenu\AdminMenu;
    use App\PostType;

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

    foreach (app(PostType::class)->list as $postType) {
        app('menu.admin-bar')->add(AdminMenu::make($postType['title'])
            ->parent(__('New'))
            ->link(fn () => route('editor', $postType['name'])));
    }

    $list = collect(app('menu.admin-bar')->list)->sortBy('order');
@endphp

@if (auth()->check())
    <flux:navbar>
        @foreach ($list->filter(fn($item) => !$item->parent) as $item)
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
    </flux:navbar>
@endif