<?php

namespace Components\Hero; // TODO: lets maybe break PSR-4 even more to only Components?

class Hero extends \Illuminate\View\Component
{
    public function __construct(public $title = '') {}

    public function render()
    {
        return view('components.hero.hero');
    }
}
