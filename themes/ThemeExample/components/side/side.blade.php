<!--
    Name: Side
-->

<?php

use App\View\Components\Fields\Text;
use Livewire\Component;

new class extends Component {
    public static function position()
    {
        return 'side';
    }

    public static function fields()
    {
        return [Text::make('Title')];
    }
};

?>

@props([
    'title' => '',
])

<section>
    Side Title: {{ $title }}
</section>
