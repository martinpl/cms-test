<?php
 
use App\Plugin;
 
new class extends Livewire\Component {
    public function activate($path) {
        Plugin::activate($path);
        $this->js('location.reload()');
    }

    public function deactivate($path) {
        Plugin::deactivate($path);
        $this->js('location.reload()');
    }
} ?>

{{-- TODO: Table api --}}
<div>
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
            @if (!$plugin['mustUse'])
                @if (Plugin::isActive($plugin['path']))
                    <button wire:click="deactivate('{{ $plugin['path'] }}')">
                        Deactivate
                    </button>
                @else
                    <button wire:click="activate('{{ $plugin['path'] }}')">
                        Active
                    </button>
                @endif
            @endif
        </div>
    @endforeach
</div>