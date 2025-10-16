<?php

namespace App;

// TODO: Move to facade?
class BlockType
{
    public private(set) array $list;

    // TODO: Move to builder
    public function register($blockType, $args = [])
    {
        // TODO: Namespace to prevent conflicts?
        if (isset($this->list[$blockType])) {
            throw new \Exception("Block type '{$blockType}' already exists.");
        }

        $defaults = [
            'name' => $blockType,
        ];

        $this->list[$blockType] = array_merge($defaults, $args);
    }

    public function find($name)
    {
        if (! empty($this->list[$name])) {
            return $this->list[$name];
        }

        return null;
    }
}
