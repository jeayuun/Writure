<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a public page for an author showing all their posts.
     */
    public function show(Request $request, $username)
    {
        $author = User::where('username', $username)->firstOrFail();

        $lang = $request->get('lang', config('app.fallback_locale'));

        $posts = $author->posts()
            ->where('status', 'published')
            ->whereHas('translations', function ($query) use ($lang) {
                $query->where('language_slug', $lang);
            })
            ->with(['translations' => function ($query) use ($lang) {
                $query->where('language_slug', $lang);
            }])
            ->latest()
            ->paginate(9); 

        return view('frontend.author', compact('author', 'posts'));
    }
}