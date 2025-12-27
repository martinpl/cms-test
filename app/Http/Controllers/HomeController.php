<?php

namespace App\Http\Controllers;

use App\AdminMenu\AdminMenu;
use App\PostTypes\AnyPost;
use Illuminate\Support\Str;

class HomeController
{
    public function __invoke()
    {
        $post = AnyPost::find(get_option('home_page'));
        $canSeeDraft = $post?->status == 'draft' && auth()->check();
        $hidden = $post?->status != 'publish' && ! $canSeeDraft;
        if (! $post || $hidden) {
            abort(404);
        }

        // TODO: merge with PostTypeController

        app('menu.admin-bar')->add(AdminMenu::make(__('Edit Page'))
            ->link(fn () => route('editor', [$post->type, $post->id]))->icon('pencil'));

        $templates = ['home', "page-{$post->id}", "page-{$post->name}", "single-{$post->type}", 'single', 'index'];
        $templates = array_map(fn ($template) => "templates.{$template}", $templates);
        $postType = Str::camel($post->type);

        return view()->first($templates, [
            $postType => $post,
            'postType' => $postType,
        ]);
    }
}
