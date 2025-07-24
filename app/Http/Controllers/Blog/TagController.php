<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $defaultLanguageSlug = Language::where('is_default', 1)->value('slug');
        $tags = Tag::with(['translations' => fn($q) => $q->where('language_slug', $defaultLanguageSlug)])->latest()->paginate(10);
        return view('admin.tags.index', compact('tags', 'defaultLanguageSlug'));
    }

    public function create()
    {
        $languages = Language::all();
        return view('admin.tags.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $languages = Language::all();
        $defaultLanguageSlug = Language::where('is_default', 1)->value('slug');

        $rules["name.{$defaultLanguageSlug}"] = 'required|string|max:255';
        $request->validate($rules, [
            "name.{$defaultLanguageSlug}.required" => 'The tag name for the default language is required.',
        ]);

        DB::beginTransaction();
        try {
            $tag = Tag::create();
            foreach ($languages as $language) {
                $slug = $language->slug;
                if (!empty($request->input("name.{$slug}"))) {
                    $tag->translations()->create([
                        'language_slug' => $slug,
                        'name' => $request->input("name.{$slug}"),
                        'slug' => Str::slug($request->input("name.{$slug}")),
                        'seo_title' => $request->input("seo_title.{$slug}"),
                        'seo_description' => $request->input("seo_description.{$slug}"),
                        'seo_keywords' => $request->input("seo_keywords.{$slug}"),
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('tags.index')->with('success', 'Tag created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Tag $tag)
    {
        $languages = Language::all();
        return view('admin.tags.edit', compact('tag', 'languages'));
    }

    public function update(Request $request, Tag $tag)
    {
        $languages = Language::all();
        $defaultLanguageSlug = Language::where('is_default', 1)->value('slug');

        $rules["name.{$defaultLanguageSlug}"] = 'required|string|max:255';
        $request->validate($rules, [
            "name.{$defaultLanguageSlug}.required" => 'The tag name for the default language is required.',
        ]);

        DB::beginTransaction();
        try {
            foreach ($languages as $language) {
                $slug = $language->slug;
                $name = $request->input("name.{$slug}");
                if (!empty($name)) {
                    $tag->translations()->updateOrCreate(
                        ['language_slug' => $slug],
                        [
                            'name' => $name,
                            'slug' => Str::slug($name),
                            'seo_title' => $request->input("seo_title.{$slug}"),
                            'seo_description' => $request->input("seo_description.{$slug}"),
                            'seo_keywords' => $request->input("seo_keywords.{$slug}"),
                        ]
                    );
                } elseif ($slug !== $defaultLanguageSlug) {
                    $tag->translations()->where('language_slug', $slug)->delete();
                }
            }
            DB::commit();
            return redirect()->route('tags.index')->with('success', 'Tag updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();
            return redirect()->route('tags.index')->with('success', 'Tag deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}