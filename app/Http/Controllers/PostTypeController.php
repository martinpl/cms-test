<?php

namespace App\Http\Controllers;

use App\AdminMenu\AdminMenu;
use App\PostTypes\AnyPost;
use Illuminate\Support\Str;

class PostTypeController
{
    public function __invoke($name, $postType)
    {
        $post = AnyPost::findBySlugStructure($name, $postType);
        if (! $post) {
            abort(404);
        }

        if ($this->wrongSlugStructure($post)) {
            return redirect()->route("single.{$post->type}", ['name' => $post->slugStructure()] + request()->query());
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

    protected function wrongSlugStructure($post)
    {
        return $post?->parent_id && request()->getPathInfo() != route("single.{$post->type}", $post->slugStructure(), absolute: false);  // TODO: route() -> $post->link()?
    }
}
