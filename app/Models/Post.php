<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'order',
        'cover_image',
        'gallery_images',
        'is_featured',
        'comment_enabled',
        'status',
        'cover_image_path',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'comment_enabled' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function translationsFrontend(): HasMany
    {
        return $this
            ->hasMany(PostTranslation::class, 'post_id')
            ->select([
                'id',
                'post_id',
                'language_slug',
                'title',
                'slug',
                'short_description',
                'updated_at'
            ]);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function post_category()
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}