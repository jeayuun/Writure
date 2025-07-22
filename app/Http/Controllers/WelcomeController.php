<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Language;
use App\Traits\LanguageValidator;

class WelcomeController extends Controller
{
    use LanguageValidator;

    public function index($lang = null)
    {
        $langSlug = $lang ?? Language::where('is_default', 1)->value('slug');
        if ($langSlug) {
            $this->validateLanguage($langSlug);
        }

        $posts = Post::query()
            ->select([
                'id',
                'category_id',
                'order',
                'cover_image',
                'status'
            ])
            ->where('status', 'published')
            ->whereHas('translations', function ($q) use ($langSlug) {
                $q->where('language_slug', $langSlug);
            })
            ->with([
                'translations' => fn($q) => $q->where('language_slug', $langSlug),
                'category' => fn($q) => $q
                    ->select(['id'])
                    ->with([
                        'translations' => fn($q) => $q->where('language_slug', $langSlug)
                    ])
            ])
            ->orderBy('order', 'asc')
            ->latest()
            ->paginate(6);

        return view('welcome', compact('posts', 'langSlug'));
    }
}