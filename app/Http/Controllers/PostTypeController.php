<?php

namespace App\Http\Controllers;

use App\AdminMenu\AdminMenu;
use App\Models\Post;
use Illuminate\Support\Str;

class PostTypeController
{
    public function __invoke($name, $postType)
    {
        $post = Post::where('type', $postType)->where('name', $name)->first();
        if (! $post) {
            abort(404);
        }

        app('menu.admin-bar')->add(AdminMenu::make(__('Edit Page'))
            ->link(fn () => route('editor', [$post->type, $post->id])));

        $templates = ["page-{$post->id}", "page-{$post->name}", "single-{$postType}", 'single', 'index'];
        $templates = array_map(fn ($template) => "templates.{$template}", $templates);
        $postType = Str::camel($postType);

        return view()->first($templates, [
            $postType => $post,
            'postType' => $postType,
        ]);
    }
}
