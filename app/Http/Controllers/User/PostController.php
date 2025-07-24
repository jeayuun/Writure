<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create()
    {
        return view('user.posts.create');
    }

    public function store(Request $request)
    {   
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $post = new \App\Models\Post([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'status' => 'draft', 
            ]);

            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $uniqueFullPath = generateUniqueFilePath(public_path('uploads/posts'), $originalName, 'webp');
                $relativePath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $uniqueFullPath);

                convertToWebP($file, $relativePath);

                $post->cover_image = $relativePath;
            }

            $post->save();

            $defaultLanguage = \App\Models\Language::where('is_default', 1)->first();
            if ($defaultLanguage) {
                $post->translations()->create([
                    'language_slug' => $defaultLanguage->slug,
                    'title' => $request->title,
                    'content' => $request->get('content'),
                    'slug' => \Illuminate\Support\Str::slug($request->title),
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('user.dashboard')->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }
}