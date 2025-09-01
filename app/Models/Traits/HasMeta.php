<?php

namespace App\Models\Traits;

use App\Models\Meta;

trait HasMeta
{
    public static function bootHasMeta()
    {
        static::deleting(function ($model) {
            $model->meta()->delete();
        });
    }

    public function getMetaAttribute()
    {
        return $this->meta()->get()->pluck('value', 'key')->toArray();
    }

    public function meta()
    {
        return $this->morphMany(Meta::class, 'metable');
    }

    public function getMeta($key, $default = null)
    {
        return $this->meta()->where('key', $key)->value('value') ?? $default;
    }

    public function setMeta($key, $value)
    {
        return $this->meta()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function deleteMeta($key)
    {
        return $this->meta()->where('key', $key)->delete();
    }
}
