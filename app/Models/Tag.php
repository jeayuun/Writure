<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [];

    public function translations()
    {
        return $this->hasMany(TagTranslation::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }
    
    /**
     * Get the translation for a given language code.
     *
     * @param string $languageCode
     * @return \App\Models\TagTranslation|null
     */
    public function translate($languageCode)
    {
        return $this->translations()->where('language_slug', $languageCode)->first();
    }
}
