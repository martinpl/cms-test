<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostTypeController
{
    public function __invoke($name, $postType)
    {
        $post = Post::where('type', $postType)->where('name', $name)->first();
        $templates = ["page-{$post->id}", "page-{$post->name}", "single-{$postType}", 'single', 'index'];
        $templates = array_map(fn ($template) => "templates.{$template}", $templates);
        $postType = Str::camel($postType);

        return view()->first($templates, [
            $postType => $post,
            'postType' => $postType,
        ]);
    }
}
