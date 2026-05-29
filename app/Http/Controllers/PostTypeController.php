<?php

namespace App\Http\Controllers;

use App\PostTypes\AnyPost;
use App\PostTypes\PostType;

class PostTypeController extends Controller
{
    public function __invoke($name, $postType)
    {
        $post = AnyPost::findBySlugStructure($name, $postType);

        if ($this->wrongSlugStructure($post)) {
            return redirect()->route(
                "single.{$post->type}",
                ['name' => $post->slugStructure()] + request()->query(),
            );
        }

        return $this->render($post, [
            "page-{$post?->id}",
            "page-{$post?->name}",
            "single-{$postType}",
            'single',
            'index',
        ]);
    }

    protected function wrongSlugStructure(?PostType $post): bool
    {
        return $post?->parent_id && request()->getPathInfo() !== $post->link(false);
    }
}
