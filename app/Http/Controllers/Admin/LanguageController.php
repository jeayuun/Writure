<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = Language::paginate(10);
        return view('admin.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:languages,slug',
            'flag' => 'nullable|string|max:255',
            'is_default' => 'boolean',
            'status' => 'boolean',
        ]);

        if ($request->has('is_default')) {
            Language::where('is_default', 1)->update(['is_default' => 0]);
        }

        Language::create($validated);

        return redirect()->route('languages.index')->with('success', 'Language created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     * The method now accepts an $id parameter.
     */
    public function edit($id)
    {
        $language = Language::findOrFail($id); // Find the language by its ID
        return view('admin.languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     * The method now accepts an $id parameter.
     */
    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id); // Find the language by its ID

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:languages,slug,' . $language->id,
            'flag' => 'nullable|string|max:255',
            'is_default' => 'boolean',
            'status' => 'boolean',
        ]);

        if ($request->has('is_default')) {
            Language::where('is_default', 1)->update(['is_default' => 0]);
        }

        $language->update($validated);

        return redirect()->route('languages.index')->with('success', 'Language updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Language $language)
    {
        $language->delete();
        return redirect()->route('languages.index')->with('success', 'Language deleted successfully.');
    }
}
