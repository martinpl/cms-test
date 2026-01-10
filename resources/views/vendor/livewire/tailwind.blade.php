@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-end gap-8 px-4">
        <div class="text-sm">
            {{ $paginator->total() }} {!! __('items') !!}
        </div>
        <div class="ml-auto flex items-center gap-2 lg:ml-0">
            <x-button variant="outline" class="hidden h-8 w-8 p-0 lg:flex" wire:click="gotoPage('1')" x-on:click="{{ $scrollIntoViewJsSnippet }}" :disabled="$paginator->onFirstPage()">
                <span class="sr-only">Go to first page</span>
                <x-icon name="chevrons-left" />
            </x-button>
            <x-button variant="outline" class="size-8" size="icon" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" :disabled="$paginator->onFirstPage()">
                <span class="sr-only">Go to previous page</span>
                <x-icon name="chevron-left" />
            </x-button>
            <div class="text-sm">
                {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
            </div>
            <x-button variant="outline" class="size-8" size="icon" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" :disabled="!$paginator->hasMorePages()">
                <span class="sr-only">Go to next page</span>
                <x-icon name="chevron-right" />
            </x-button>
            <x-button variant="outline" class="hidden size-8 lg:flex" size="icon" wire:click="gotoPage('{{ $paginator->lastPage() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" :disabled="!$paginator->hasMorePages()">
                <span class="sr-only">Go to last page</span>
                <x-icon name="chevrons-right" />
            </x-button>
        </div>
    </nav>
@endif
