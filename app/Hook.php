<?php

namespace App;

class Hook
{
    protected $hooks = [];

    public function addFilter($tag, $callback, $priority = 10)
    {
        $this->hooks[$tag][$priority][] = $callback;
    }

    public function applyFilters($tag, $value, ...$args)
    {
        if (empty($this->hooks[$tag])) {
            return $value;
        }

        ksort($this->hooks[$tag]);
        foreach ($this->hooks[$tag] as $callbacks) {
            foreach ($callbacks as $callback) {
                $value = $callback($value, ...$args);
            }
        }

        return $value;
    }

    public function addAction($tag, $callback, $priority = 10)
    {
        return $this->addFilter($tag, $callback, $priority);
    }

    public function doAction($tag, ...$args)
    {
        $this->applyFilters($tag, null, ...$args);
    }
}
