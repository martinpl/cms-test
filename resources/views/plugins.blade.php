<x-layouts.app :title="__('Plugins')">
    <flux:heading size="xl">Plugins</flux:heading>
    @foreach (App\Plugin::list() as $plugin)
        <div>
            {{ $plugin['name'] }} 
            @if ($plugin['version'])
                ({{ $plugin['version'] }})
            @endif
            @if ($plugin['author'])
                | {{ $plugin['author'] }}
            @endif
            @if ($plugin['description'])
                | {{ $plugin['description'] }}
            @endif
        </div>
    @endforeach
</x-layouts.app>
