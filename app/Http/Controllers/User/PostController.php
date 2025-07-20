<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        // Return the new, correct view for the user
        return view('user.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id(); // Assign the post to the logged-in user

        // Handle Cover Image Upload
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = ImageHelper::upload($request->file('cover_image'), 'posts');
        }

        // Handle Gallery Photos Upload
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = ImageHelper::upload($image, 'posts/gallery');
            }
            $data['gallery'] = json_encode($galleryPaths);
        }

        // By default, user posts can be set to 'pending_review'
        $data['status'] = 'pending_review';

        // Create and save the post with the prepared data
        $post = new Post();
        $post->fill($data)->save();

        // Handle Category relationship
        if ($request->has('category_id')) {
            $post->categories()->sync([$request->input('category_id')]);
        }
        
        // Handle Tags relationship
        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags'));
        }

        // Handle Translations
        if ($request->has('translations')) {
            foreach ($request->translations as $lang => $translationData) {
                // Ensure translation data is not empty before creating
                if (!empty($translationData['title'])) {
                    $post->translations()->create(
                        $translationData + ['language_code' => $lang]
                    );
                }
            }
        }

        // After saving, redirect the user back to their dashboard with a success message.
        return redirect()->route('user.dashboard')->with('success', 'Post submitted for review successfully!');
    }
}
