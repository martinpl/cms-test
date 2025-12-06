<?php

namespace App\AdminMenu;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AdminMenu
{
    public string $name {
        get => Str::slug($this->title);
    }

    public protected(set) string $title;

    public protected(set) string $icon;

    public protected(set) int $order = 0;

    public protected(set) ?string $parent = null;

    public protected(set) ?string $group = null;

    // TODO: helper may bump readability
    public protected(set) string|\Closure|null $link = null {
        get => $this->link ? ($this->link)() : null;
    }

    public protected(set) bool|\Closure|null $current = null {
        get => $this->current ? ($this->current)() : request()->routeIs($this->name);
    }

    public static function make(string $title): static
    {
        $item = new static;
        $item->title = $title;

        return $item;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    // TODO: refactor | don't do only view? | rename? | support external plugins, themes views
    public function route(?string $uri = null, ?string $view = null): static
    {
        if (! $uri) {
            $uri = $this->name;
        }

        if (! $view) {
            $view = $this->name;
        }

        // TODO: remove group
        Route::prefix('dashboard')
            ->middleware(['web', 'auth', 'verified'])
            ->group(function () use ($uri, $view) {
                Route::view($uri, $view)
                    ->name($this->name);

            });

        $this->link(fn () => route($this->name));

        return $this;
    }

    public function link(\Closure $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function order(int $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function group(string $group): static
    {
        $this->group = $group;

        return $this;
    }

    public function parent(string $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function current(\Closure $current): static
    {
        $this->current = $current;

        return $this;
    }
}
