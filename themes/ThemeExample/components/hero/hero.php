<?php

use App\View\Components\Fields\Media;
use App\View\Components\Fields\Repeater;
use App\View\Components\Fields\Text;
use Livewire\Component;

new class extends Component
{
    // TODO: Move to attribute to fields or API?
    public static function position()
    {
        // return 'side';

        return 'content';
    }

    public static function fields()
    {
        return [
            Text::make('Title'),
            Media::make('Image'),
            Repeater::make('Repeater')
                ->schema([
                    Text::make('Title'),
                    Media::make('Image'),
                    Repeater::make('Repeater')
                        ->schema([
                            Text::make('Title'),
                            Media::make('Image'),
                        ]),
                ]),
        ];
    }
};
