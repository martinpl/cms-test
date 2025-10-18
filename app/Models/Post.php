<?php

namespace App\Models;

use App\Hook;
use App\Models\Traits\HasMeta;
use App\PostType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use HasMeta;

    protected $fillable = [
        'type',
        'status',
        'title',
        'content',
        'user_id',
    ];

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
            get: fn ($value) => app(Hook::class)->applyFilters('post.content', $value, $this),
            set: fn ($value) => app(Hook::class)->applyFilters('post.save.content', $value, $this),
        );
    }

    public function link()
    {
        $route = app(PostType::class)->find($this->type)['route'];
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
}
