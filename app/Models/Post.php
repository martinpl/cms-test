<?php

namespace App\Models;

use App\Models\Traits\HasMeta;
use App\PostType;
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

    public function link()
    {
        $route = app(PostType::class)->find($this->type)['route'];

        return route('single', [
            $route,
            $this->name,
        ]);
    }
}
