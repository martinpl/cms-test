<?php

namespace App\PostTypes;

use App\Hook;
use App\Models\Taxonomy;
use App\Models\Traits\HasMeta;
use App\PostTypeRegistry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

abstract class PostType extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;
    use HasMeta;

    protected $table = 'posts';

    protected $fillable = [
        'type',
        'status',
        'title',
        'content',
        'user_id',
    ];

    public static $type = null;

    public function __construct()
    {
        parent::__construct();

        if (static::$type) {
            $this->attributes['type'] = static::$type;
        }
    }

    protected static function booted()
    {
        if (static::$type) {
            static::addGlobalScope('type', function (Builder $builder) {
                $builder->where('type', static::$type);
            });
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->name = $post->generateUniqueSlug($post->type, $post->title);
        });
    }

    protected function generateUniqueSlug($type, $title)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $i = 1;
        while (self::where('type', $type)->where('name', $slug)->exists()) {
            $slug = $baseSlug.'-'.$i;
            $i++;
        }

        return $slug;
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => app(Hook::class)->applyFilters('post.content', $value, $attributes),
            set: fn ($value, $attributes) => app(Hook::class)->applyFilters('post.save.content', $value, $attributes),
        );
    }

    public function link()
    {
        $route = app(PostTypeRegistry::class)->find($this->type)['route'];
        if ($route === false) {
            return null;
        }

        return route("single.{$this->type}", $this->name);
    }

    public function terms($taxonomyType = null)
    {
        return $this->belongsToMany(Taxonomy::class, 'term_relationships', 'post_id', 'term_id')
            ->when($taxonomyType, function ($query) use ($taxonomyType) {
                $query->where('type', $taxonomyType);
            });
    }

    protected static function register()
    {
        app(PostTypeRegistry::class)->register(static::class::$type, static::config());
    }
}
