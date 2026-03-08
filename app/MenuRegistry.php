<?php

namespace App;

class MenuRegistry
{
    public private(set) array $list;

    public function register($locations)
    {
        foreach ($locations as $key => $description) {
            $this->list[$key] = $description;
        }
    }
}
