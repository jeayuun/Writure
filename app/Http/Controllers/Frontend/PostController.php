<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Tag;
use App\Traits\LanguageValidator;

class PostController extends BaseFrontendController
{
    use LanguageValidator;

    public function show($lang = null, $slug)
    {
        $languages = Language::all();

        if ($lang) {
            $this->validateLanguage($lang);
        }

        $translation = PostTranslation::where('slug', $slug)
            ->where('language_slug', $lang)
            ->with([
                'post.user',
                'post.category.translations' => function ($q) use ($lang) {
                    $q->where('language_slug', $lang);
                },
                'post.tags.translations' => function ($q) use ($lang) {
                    $q->select('tag_id', 'name', 'slug')->where('language_slug', $lang);
                },
            ])
            ->firstOrFail();

        $morePosts = Post::where('user_id', $translation->post->user_id)
            ->where('id', '!=', $translation->post_id) 
            ->where('status', 'published')
            ->with(['translations' => function ($query) use ($lang) {
                $query->where('language_slug', $lang);
            }, 'user'])
            ->latest()
            ->take(3)
            ->get();

        return view('frontend.post', compact('translation', 'morePosts'));
    }

    public function getPostsByCategory($lang, $slug)
    {
        $this->validateLanguage($lang);

        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::whereHas('translations', function ($query) use ($lang) {
            $query->where('language_slug', $lang);
        })
            ->where('status', 'published')
            ->whereHas('category', function ($query) use ($category) {
                $query->where('id', $category->id);
            })
            ->with(['translations' => function ($query) use ($lang) {
                $query->where('language_slug', $lang);
            }, 'user'])
            ->latest()
            ->paginate(10);

        return view('frontend.category_posts', compact('posts', 'category'));
    }
}
