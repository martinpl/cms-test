<?php

namespace App;

class Role
{
    public protected(set) array $list;

    public function register($name, $title, $capabilities = [])
    {
        // TODO: We probably want here object
        $this->list[$name] = [
            'name' => $name,
            'title' => $title,
            'capabilities' => $capabilities,
        ];
    }

    public function addCapability($role, $capability)
    {
        if (isset($this->list[$role])) {
            $this->list[$role]['capabilities'][] = $capability;
        }
    }

    public function removeCapability($role, $capability)
    {
        if (isset($this->list[$role])) {
            $this->list[$role]['capabilities'] = array_filter(
                $this->list[$role]['capabilities'],
                fn ($cap) => $cap !== $capability
            );
        }
    }
}
