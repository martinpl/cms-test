<?php

namespace App\Foundation;

class Menu
{
    protected array $list = [];

    public function register($locations): void
    {
        foreach ($locations as $key => $description) {
            $this->list[$key] = $description;
        }
    }

    public function list(): array
    {
        return $this->list;
    }
}
