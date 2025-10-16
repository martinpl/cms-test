@php($postType = app(App\PostType::class)->find(request()->route('postType')))
<x-layouts.app :title="__('Editor')">
    {!! $postType['editor']::render() !!}
</x-layouts.app>

