<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;

class HomeController
{
    public function __invoke()
    {
        $post = Post::find(get_option('home_page'));
        if (! $post) {
            abort(404);
        }

        // TODO: merge with PostTypeController

        $templates = ['home', "page-{$post->id}", "page-{$post->name}", "single-{$post->type}", 'single', 'index'];
        $templates = array_map(fn ($template) => "templates.{$template}", $templates);
        $postType = Str::camel($post->type);

        return view()->first($templates, [
            $postType => $post,
            'postType' => $postType,
        ]);
    }
}
