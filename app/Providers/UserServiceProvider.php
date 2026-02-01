<?php

namespace App\Providers;

use App\AdminMenu\AdminMenu;
use App\Role;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app('menu.admin')->add(AdminMenu::make('Users')
            ->livewire()
            ->order(1)
            ->icon('user'));

        app(Role::class)->register('super_admin', 'Super Admin');
        app(Role::class)->register('administrator', 'Administrator');
        app(Role::class)->register('editor', 'Editor');
        app(Role::class)->register('author', 'Author');
        app(Role::class)->register('contributor', 'Contributor');
        app(Role::class)->register('user', 'User');
    }
}
