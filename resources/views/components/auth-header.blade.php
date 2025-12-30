@props(['title', 'description'])

<div class="flex w-full flex-col text-center gap-2">
    <x-card.title class="text-2xl font-medium">{{ $title }}</x-card.title>
    <x-card.description>{{ $description }}</x-card.description>
</div>
