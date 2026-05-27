<?php

namespace App\Providers;

use App\Actions\AddEditorMetaboxes;
use App\AdminMenu\AdminMenu;
use App\AdminMenu\AdminMenuList;
use App\BlockEditor;
use App\ClasslessLivewire\Compiler;
use App\ClasslessLivewire\Factory;
use App\ClasslessLivewire\Finder;
use App\Foundation\BlockType;
use App\Foundation\Hook;
use App\Foundation\Menu;
use App\Foundation\Metabox;
use App\Foundation\PostType;
use App\Foundation\Taxonomy;
use App\Models\Option;
use App\Role;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Compiler\CacheManager;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->classlessLivewire();
        $this->app->singleton(Hook::class);
        $this->app->singleton(Metabox::class);
        $this->app->singleton(PostType::class);
        $this->app->singleton(Taxonomy::class);
        $this->app->singleton(BlockType::class);
        BlockEditor::register();
        $this->app->singleton('menu.admin', fn () => new AdminMenuList);
        $this->app->singleton('menu.admin-bar', fn () => new AdminMenuList);
        $this->app->singleton(Role::class);
        $this->app->singleton(Menu::class);
        app()->instance('post', null);
    }

    public function boot(): void
    {
        if (app()->runningInConsole()) { // TODO: That should be check for migration command
            return;
        }

        // TODO: Move out
        ComponentAttributeBag::macro('buttonTag', function () {
            $hasHref = $this->has('href') && $this->get('href') !== null;

            return $hasHref ? 'a' : 'button';
        });

        ComponentAttributeBag::macro('asChild', function ($slot) {
            return new HtmlString(preg_replace(
                '/^<([a-z0-9-]+)/i',
                '<$1 '.
                    $this,
                ltrim($slot),
                1,
            ));
        });

        app('menu.admin')->add(AdminMenu::make(__('Dashboard'))
            ->page('/')
            ->icon('house'));

        app('menu.admin')->add(AdminMenu::make(__('Settings'))
            ->order(2)
            ->livewire()
            ->icon('sliders-vertical'));

        $this->app->singleton('options', function () {
            return Option::where('autoload', true)->select('name', 'value')->get()->pluck('value', 'name')->toArray();
        });

        // TODO: Move to register() in editor
        (new AddEditorMetaboxes)();
    }

    // TODO: Shorthand implementation + we could try push that to Livewire
    private function classlessLivewire()
    {
        $this->app->singleton('livewire.factory', function ($app) {
            return new Factory(
                $app['livewire.finder'],
                $app['livewire.compiler']
            );
        });

        $this->app->singleton('livewire.finder', function () {
            $finder = new Finder;

            $finder->addLocation(classNamespace: config('livewire.class_namespace'));

            return $finder;
        });

        $this->app->singleton('livewire.compiler', function () {
            return new Compiler(
                new CacheManager(
                    rtrim(config('view.compiled'), '/\\').'/livewire'
                )
            );
        });
    }
}
