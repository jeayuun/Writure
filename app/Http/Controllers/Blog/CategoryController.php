<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $defaultLanguageSlug = Language::where('is_default', 1)->value('slug');
        
        $categories = Category::with(['translations' => function ($query) use ($defaultLanguageSlug) {
            $query->where('language_slug', $defaultLanguageSlug);
        }])->latest()->paginate(10);

        return view('admin.categories.index', compact('categories', 'defaultLanguageSlug'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.categories.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $languages = Language::all();
        $defaultLanguageSlug = Language::where('is_default', 1)->value('slug');

        $rules = [];
        // The only required field is the name for the default language.
        $rules["name.{$defaultLanguageSlug}"] = 'required|string|max:255';

        // All other fields, including SEO Title, are optional for all languages.
        foreach ($languages as $language) {
            $slug = $language->slug;
            $rules["seo_title.{$slug}"] = 'nullable|string|max:255';
            $rules["seo_description.{$slug}"] = 'nullable|string';
            $rules["seo_keywords.{$slug}"] = 'nullable|string';
        }
        
        $request->validate($rules, [
            "name.{$defaultLanguageSlug}.required" => 'The category name for the default language is required.',
        ]);

        DB::beginTransaction();
        try {
            $category = Category::create();

            foreach ($languages as $language) {
                $slug = $language->slug;
                $name = $request->input("name.{$slug}");

                // Only create a translation if a name for that language was provided
                if (!empty($name)) {
                    $category->translations()->create([
                        'language_slug' => $slug,
                        'name' => $name,
                        'slug' => Str::slug($name),
                        'seo_title' => $request->input("seo_title.{$slug}"),
                        'seo_description' => $request->input("seo_description.{$slug}"),
                        'seo_keywords' => $request->input("seo_keywords.{$slug}"),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $languages = Language::all();
        return view('admin.categories.edit', compact('category', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $languages = Language::all();
        $defaultLanguageSlug = Language::where('is_default', 1)->value('slug');

        $rules = [];
        $rules["name.{$defaultLanguageSlug}"] = 'required|string|max:255';

        $request->validate($rules);

        DB::beginTransaction();
        try {
            foreach ($languages as $language) {
                $slug = $language->slug;
                $name = $request->input("name.{$slug}");

                if (!empty($name)) {
                    $category->translations()->updateOrCreate(
                        ['language_slug' => $slug],
                        [
                            'name' => $name,
                            'slug' => Str::slug($name),
                            'seo_title' => $request->input("seo_title.{$slug}"),
                            'seo_description' => $request->input("seo_description.{$slug}"),
                            'seo_keywords' => $request->input("seo_keywords.{$slug}"),
                        ]
                    );
                } else {
                    $category->translations()->where('language_slug', $slug)->delete();
                }
            }
            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}