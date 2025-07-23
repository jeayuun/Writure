<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch categories and tags to display on the form
        $categories = Category::with('translations')->get();
        $tags = Tag::with('translations')->get();
        $defaultLanguage = Language::where('is_default', 1)->first();

        return view('user.posts.create', compact('categories', 'tags', 'defaultLanguage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $defaultLanguage = Language::where('is_default', 1)->first();

        $validated = $request->validate([
            'translations.' . $defaultLanguage->slug . '.title' => 'required|string|max:255',
            'translations.' . $defaultLanguage->slug . '.content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->category_id = $request->category_id;
        $post->status = 'draft'; // Default to draft

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('post-covers', 'public');
            $post->cover_image = $path;
        }

        $post->save();

        // Handle the translation for the default language
        $translationData = $validated['translations'][$defaultLanguage->slug];
        $post->translations()->create([
            'language_slug' => $defaultLanguage->slug,
            'title' => $translationData['title'],
            'slug' => Str::slug($translationData['title']),
            'content' => $translationData['content'],
            'author' => Auth::user()->name,
            'short_description' => Str::limit(strip_tags($translationData['content']), 150),
        ]);

        // Attach tags if any were selected
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        return redirect()->route('user.profile', ['username' => Auth::user()->username])
                         ->with('success', 'Post created successfully! It is currently a draft.');
    }
}
