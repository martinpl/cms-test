<!--
    Name: Side
-->

<?php

use App\Facades\Fields;
use App\View\Components\Fields\Text;
use Livewire\Component;

new class extends Component {

    public static function register()
    {
        Fields::make()
            ->fields([Text::make('Title')])
            ->location('block.side', 'side')
            ->register();
    }
};

?>

@props([
    'title' => '',
])

<section>
    Side Title: {{ $title }}
</section>
