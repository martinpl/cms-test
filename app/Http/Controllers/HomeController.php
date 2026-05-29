<?php

namespace App\Http\Controllers;

use App\PostTypes\AnyPost;

class HomeController extends Controller
{
    public function __invoke()
    {
        $post = AnyPost::find(get_option('home_page'));

        return $this->render($post, [
            'home',
            "page-{$post?->id}",
            "page-{$post?->name}",
            "single-{$post?->type}",
            'single',
            'index',
        ]);
    }
}
