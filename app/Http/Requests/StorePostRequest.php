<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueSlugPerLanguage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $translations = $this->input('translations', []);
        
        foreach ($translations as $lang => $data) {
            if (empty($data['slug']) && !empty($data['title'])) {
                $translations[$lang]['slug'] = $this->generateUniqueSlug($data['title'], $lang);
            }
        }
        
        $this->merge(['translations' => $translations]);
    }

    private function generateUniqueSlug(string $title, string $language, ?int $ignorePostId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $language, $ignorePostId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            
            if ($counter > 100) {
                $slug = $originalSlug . '-' . time();
                break;
            }
        }
        
        return $slug;
    }
    

    private function slugExists(string $slug, string $language, ?int $ignorePostId = null): bool
    {
        $query = DB::table('post_translations')
            ->where('slug', $slug)
            ->where('language_slug', $language);
            
        if ($ignorePostId) {
            $query->where('post_id', '!=', $ignorePostId);
        }
        
        return $query->exists();
    }

    public function rules(): array
    {
        $defaultLangSlug = \App\Models\Language::where('is_default', 1)->value('slug');

        $rules = [
            'category_id' => ['nullable', 'exists:categories,id'],
            'order' => ['nullable', 'integer'],
            'cover_image' => ['nullable', 'image'],
            'gallery_images.*' => ['nullable', 'image'],
            'is_featured' => ['nullable', 'boolean'],
            'comment_enabled' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'exists:tags,id'],
        ];

        foreach ($this->input('translations', []) as $lang => $translation) {
            $required = ($lang === $defaultLangSlug) ? 'required' : 'nullable';

            if ($required === 'nullable' && empty($translation['title'])) {
                continue;
            }

            $rules["translations.$lang.title"] = [$required, 'string', 'max:255'];
            $rules["translations.$lang.slug"] = [
                $required,
                'string',
                'max:255',
                new UniqueSlugPerLanguage($lang)
            ];
            $rules["translations.$lang.short_description"] = ['nullable', 'string'];
            $rules["translations.$lang.content"] = ['nullable', 'string'];
            $rules["translations.$lang.seo_title"] = ['nullable', 'string', 'max:255'];
            $rules["translations.$lang.seo_description"] = ['nullable', 'string', 'max:255'];
            $rules["translations.$lang.seo_keywords"] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }
}