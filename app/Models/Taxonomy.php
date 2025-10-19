<?php

namespace App\Models;

use App\Models\Traits\HasMeta;
use App\PostTypes\AnyPost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Taxonomy extends Model
{
    use HasFactory;
    use HasMeta;

    protected $table = 'terms';

    protected $fillable = [
        'type',
        'title',
        'description',
        'parent_id',
        'order',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($taxonomy) {
            $taxonomy->name = $taxonomy->generateUniqueSlug($taxonomy->type, $taxonomy->title);
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

    public function posts()
    {
        return $this->belongsToMany(AnyPost::class, 'term_relationships', 'term_id', 'post_id');
    }
}
