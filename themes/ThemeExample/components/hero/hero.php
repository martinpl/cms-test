<?php

use App\Facades\Fields;
use App\View\Components\Fields\Media;
use App\View\Components\Fields\Repeater;
use App\View\Components\Fields\Text;
use Livewire\Component;

new class extends Component
{
    public static function register()
    {
        Fields::make()
            ->fields([
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
            ])
            ->location('block', 'hero')
            ->register();
    }
};
