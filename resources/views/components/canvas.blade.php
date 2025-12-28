<!DOCTYPE html>
{{-- TODO: lang settings --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- TODO: Post title --}}
    <title>Post Title - {{ get_option('site_title') }}</title>
    {{-- TODO: favicon setting --}}
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- TODO: To remove --}}
    {{-- TODO: add livewire? --}}
</head>
{{-- TODO: custom classes hook --}}

<body {{ $attributes }}>
    <x-admin-bar /> {{-- TODO: move to hook --}}
    {{-- TODO: body open hook --}}
    {{ $slot }}
    {{-- TODO: footer hook --}}
    @fluxScripts {{-- TODO: to remove only for adminbar, repalce with livewire --}}
</body>

</html>
