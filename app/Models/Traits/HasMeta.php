<?php

namespace App\Models\Traits;

use App\Models\Meta;
use App\PostTypes\AnyPost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        if ($isEmpty) {
            return $this->deleteMeta($key);
        }

        $this->metaCache[$key] = $value;

        return $this->morphMany(Meta::class, 'metable')->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function deleteMeta($key)
    {
        unset($this->metaCache[$key]);

        return $this->morphMany(Meta::class, 'metable')->where('key', $key)->delete();
    }

    public function scopeWhereMeta(Builder $query, string $key, $value, $operator = '=')
    {
        $model = $query->getModel();

        $selector = '';
        $jsonSelector = str_contains($key, '->');
        if ($jsonSelector) {
            $selector = '->'.Str::after($key, '->');
            $key = Str::before($key, '->');
        }

        // Single string is store with quotes "value"
        if (! $jsonSelector && is_string($value)) {
            $value = Str::wrap($value, '"');
        }

        return $query->whereExists(fn ($q) => $q->select(DB::raw(1))
            ->from('meta')
            ->whereColumn('metable_id', $model->getTable().'.id')
            ->where('metable_type', get_class($model))
            ->where('key', $key)
            ->where("value{$selector}", $operator, $value)
        );
    }

    public function scopeWhereMetaIn(Builder $query, string $key, $value)
    {
        $model = $query->getModel();

        $selector = '';
        $jsonSelector = str_contains($key, '->');
        if ($jsonSelector) {
            $selector = '->'.Str::after($key, '->');
            $key = Str::before($key, '->');
        }

        return $query->whereExists(fn ($q) => $q->select(DB::raw(1))
            ->from('meta')
            ->whereColumn('metable_id', $model->getTable().'.id')
            ->where('metable_type', get_class($model))
            ->where('key', $key)
            ->whereJsonContains("meta.value{$selector}", $value)
        );
    }
}
