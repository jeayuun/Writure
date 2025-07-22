<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Traits\LanguageValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use LanguageValidator;

    public function index()
    {
        $defaultLanguage = Language::where('is_default', 1)->first();
        $langSlug = $defaultLanguage ? $defaultLanguage->slug : 'en';

        $categories = Category::with(['translations' => function ($query) use ($langSlug) {
            $query->where('language_slug', $langSlug);
        }])->paginate(10);

        return view('admin.categories.index', compact('categories', 'langSlug'));
    }

    public function create()
    {
        $languages = Language::all();
        return view('admin.categories.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.language_slug' => 'required|string|exists:languages,slug',
        ]);

        $category = Category::create();

        foreach ($validated['translations'] as $transData) {
            $category->translations()->create([
                'language_slug' => $transData['language_slug'],
                'name' => $transData['name'],
                'slug' => Str::slug($transData['name']),
            ]);
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     * The method now accepts an $id parameter.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id); // Find the category by its ID
        $languages = Language::all();
        $category->load('translations');
        return view('admin.categories.edit', compact('category', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     * The method now accepts an $id parameter.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id); // Find the category by its ID

        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.language_slug' => 'required|string|exists:languages,slug',
        ]);

        foreach ($validated['translations'] as $transData) {
            $category->translations()->updateOrCreate(
                ['language_slug' => $transData['language_slug']],
                [
                    'name' => $transData['name'],
                    'slug' => Str::slug($transData['name']),
                ]
            );
        }

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->translations()->delete();
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
