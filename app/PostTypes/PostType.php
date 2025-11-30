<?php

namespace App\PostTypes;

use App\Hook;
use App\Models\Taxonomy;
use App\Models\Traits\HasMeta;
use App\PostTypeRegistry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class PostType extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;
    use HasMeta;

    protected $table = 'posts';

    protected $fillable = [
        'type',
        'status',
        'name',
        'title',
        'content',
        'user_id',
        'parent_id',
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

        static::addGlobalScope('publish', function (Builder $builder) {
            // TODO: Too hacky
            $hasStatus = Arr::first($builder->getQuery()->wheres, fn ($i) => isset($i['column']) && $i['column'] == 'status');
            $directQuery = Arr::first($builder->getQuery()->wheres, fn ($i) => isset($i['column']) && ($i['column'] == 'name' || $i['column'] == 'id' || $i['column'] == 'posts.id')); // AnyPost::findBySlugStructure() | AnyPost::destroy() | AnyPost::find()
            $directQueryNested = Arr::first($builder->getQuery()->wheres, fn ($i) => $i['type'] == 'Nested' && in_array('id', array_column($i['query']->wheres, 'column'))); // editor->save()
            if (! $hasStatus && ! $directQuery && ! $directQueryNested) {
                $builder->where('status', 'publish');
            }
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            if (empty($post->name)) {
                $post->name = $post->generateUniqueSlug($post->title);
            }

            if ($post->isDirty('name')) {
                $post->name = $post->generateUniqueSlug($post->name);
            }
        });
    }

    protected function generateUniqueSlug($title)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $i = 1;
        while (self::where('type', $this->type)->where('name', $slug)->exists()) {
            $slug = $baseSlug.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function getMorphClass()
    {
        return "post_type.{$this->type}";
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

        return route("single.{$this->type}", $this->slugStructure());
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function slugStructure()
    {
        return $this->parent ? $this->parent->slugStructure().'/'.$this->name : $this->name;
    }

    public static function findBySlugStructure($path, $postType)
    {
        $segments = explode('/', $path);
        $page = null;
        foreach ($segments as $name) {
            $page = static::where('type', $postType)
                ->where('name', $name)
                ->when($page, fn ($q) => $q->where('parent_id', $page->id))
                ->first();

            if (! $page) {
                return null;
            }
        }

        return $page;
    }

    public function scopeWhereStatus($query, $status)
    {
        return is_array($status)
            ? $query->whereIn('status', $status)
            : $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        $search = "%{$search}%";

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', $search)->orWhere('content', 'like', $search);
        });
    }

    public function terms($taxonomyType = null)
    {
        return $this->belongsToMany(Taxonomy::class, 'term_relationships', 'post_id', 'term_id')
            ->when($taxonomyType, function ($query) use ($taxonomyType) {
                $query->where('type', $taxonomyType);
            });
    }

    public function trash()
    {
        $this->status = 'trash';
        $this->save();
    }

    public function untrash()
    {
        $this->status = 'draft';
        $this->save();
    }
}
