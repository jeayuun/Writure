<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $defaultLanguage = Language::where('is_default', 1)->first();
        $langSlug = $defaultLanguage ? $defaultLanguage->slug : 'en';

        // Use paginate() instead of get() to enable pagination
        $tags = Tag::with(['translations' => function ($query) use ($langSlug) {
            $query->where('language_slug', $langSlug);
        }])->paginate(10); // Adjust items per page as needed

        return view('admin.tags.index', compact('tags', 'langSlug'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.tags.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.language_slug' => 'required|string|exists:languages,slug',
        ]);

        $tag = Tag::create();

        foreach ($validated['translations'] as $transData) {
            $tag->translations()->create([
                'language_slug' => $transData['language_slug'],
                'name' => $transData['name'],
                'slug' => Str::slug($transData['name']),
            ]);
        }

        return redirect()->route('tags.index')->with('success', 'Tag created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $languages = Language::all();
        $tag->load('translations');
        return view('admin.tags.edit', compact('tag', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.language_slug' => 'required|string|exists:languages,slug',
        ]);

        foreach ($validated['translations'] as $transData) {
            $tag->translations()->updateOrCreate(
                ['language_slug' => $transData['language_slug']],
                [
                    'name' => $transData['name'],
                    'slug' => Str::slug($transData['name']),
                ]
            );
        }

        return redirect()->route('tags.index')->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->translations()->delete();
        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Tag deleted successfully.');
    }
}
