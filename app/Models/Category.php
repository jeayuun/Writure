<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function translationsFrontend(): HasMany
    {
        return $this
            ->hasMany(CategoryTranslation::class, 'category_id')
            ->select([
                'id',
                'category_id',
                'name',
                'slug'
            ]);
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category');
    }

    /**
     * Get the translation for a given language code.
     *
     * @param string $languageCode
     * @return \App\Models\CategoryTranslation|null
     */
    public function translate($languageCode)
    {
        return $this->translations()->where('language_slug', $languageCode)->first();
    }
}
