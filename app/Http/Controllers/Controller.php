<?php

namespace App\Http\Controllers;

use App\AdminMenu\AdminMenu;
use App\PostTypes\PostType;

abstract class Controller
{
    protected function render(?PostType $post, array $templates)
    {
        if (! $post || $this->isHidden($post)) {
            abort(404);
        }

        app('menu.admin-bar')->add(AdminMenu::make(__('Edit Page'))
            ->link(fn () => route('editor', [$post->type, $post->id]))
            ->icon('pencil'));
        app()->instance('post', $post);

        $templates = array_map(fn ($template) => "templates.{$template}", $templates);

        return view()->first($templates);
    }

    protected function isHidden(PostType $post): bool
    {
        $canSeeDraft = $post->status == 'draft' && auth()->check(); // TODO: Permission

        return $post->status !== 'publish' && ! $canSeeDraft;
    }
}
