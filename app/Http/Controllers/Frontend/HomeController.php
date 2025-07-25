<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Language;
use App\Models\Post;
use App\Traits\LanguageValidator;

class HomeController extends BaseFrontendController
{
    use LanguageValidator;

    public function index($lang = null)
    {
        $langSlug = $lang ?? Language::where('is_default', 1)->value('slug');
        $this->validateLanguage($langSlug);

        $posts = Post::query()
            ->select([
                'id',
                'user_id',
                'category_id',
                'order',
                'cover_image',
                'status',
                'created_at'
            ])
            ->where('status', 'published')
            ->whereHas('translations', function ($q) use ($langSlug) {
                $q->where('language_slug', $langSlug);
            })
            ->with([
                'user',
                'translations' => fn($q) => $q->where('language_slug', $langSlug),
                'category' => fn($q) => $q
                    ->select(['id'])
                    ->with([
                        'translations' => fn($q) => $q->where('language_slug', $langSlug)
                    ])
            ])
            ->orderBy('order', 'asc')
            ->latest()
            ->get();

        return view('frontend.home', compact('posts', 'langSlug'));
    }
}
