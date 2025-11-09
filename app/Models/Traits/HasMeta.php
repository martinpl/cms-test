<?php

namespace App\Models\Traits;

use App\Models\Meta;
use App\PostTypes\AnyPost;

trait HasMeta
{
    private $metaCache;

    public static function bootHasMeta()
    {
        static::deleting(function ($model) {
            $model->morphMany(Meta::class, 'metable')->delete();
        });
    }

    public function meta($key = null, $default = null)
    {
        if (! $this->metaCache) {
            $this->metaCache = $this->morphMany(Meta::class, 'metable')->get()->pluck('value', 'key')->toArray();
        }

        if ($key === null) {
            return $this->metaCache;
        }

        return $this->metaCache[$key] ?? $default;
    }

    public static function getMeta($metableId, $key, $default = null)
    {
        $meta = Meta::where('metable_id', $metableId)
            ->when(static::class != AnyPost::class, fn ($q) => $q->where('metable_type', static::class)) // TODO: AnyTerm, ...?
            ->where('key', $key)
            ->first();

        return $meta ? $meta->value : $default;
    }

    public function setMeta($key, $value)
    {
        $isEmpty = $value === '';

        if (! $isEmpty) {
            $this->metaCache[$key] = $value;

            return $this->morphMany(Meta::class, 'metable')->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($isEmpty) {
            return $this->deleteMeta($key);
        }
    }

    public function deleteMeta($key)
    {
        unset($this->metaCache[$key]);

        return $this->morphMany(Meta::class, 'metable')->where('key', $key)->delete();
    }
}
