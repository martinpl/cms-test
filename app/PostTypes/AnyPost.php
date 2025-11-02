<?php

namespace App\PostTypes;

use App\PostTypeRegistry;

class AnyPost extends PostType
{
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $class = app(PostTypeRegistry::class)->find($attributes->type)['class'];
        if ($class) {
            $model = new $class;
            $model = $model->newInstance([], true);
        } else {
            $model = $this->newInstance([], true);
        }

        $model->setRawAttributes((array) $attributes, true);

        $model->setConnection($connection ?? $this->getConnectionName());

        $model->fireModelEvent('retrieved', false);

        return $model;
    }

    public function scopeWhereType($query, $type)
    {
        return is_array($type)
            ? $query->whereIn('type', $type)
            : $query->where('type', $type);
    }
}
