<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $languages = Language::all();
        $langSlug = Language::where('is_default', 1)->first()?->slug ?? 'en';

        $posts = Post::query()
            ->select([
                'id',
                'category_id',
                'order',
                'status'
            ])
            ->with([
                'translations' => fn($q) => $q->select('id', 'post_id', 'language_slug', 'title', 'slug'),
                'translations.language' => fn($q) => $q->select('id', 'slug', 'flag')
            ])
            ->latest()
            ->paginate(10);

        return view('admin.posts.index', compact('posts', 'langSlug', 'languages'));
    }

    public function create()
    {
        $languages = Language::all();
        $categories = Category::with('translations')->get();
        $tags = Tag::with('translations')->get();
        return view('admin.posts.create', compact('languages', 'categories', 'tags'));
    }

    public function store(StorePostRequest $request)
    {
        DB::beginTransaction();

        try {
            $post = new Post();
            $post->user_id = auth()->id();
            $post->category_id = $request->category_id;
            $post->order = $request->order ?? 0;
            $post->is_featured = $request->has('is_featured');
            $post->comment_enabled = $request->has('comment_enabled');
            $post->status = $request->status ?? 'draft';

            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $uniqueFullPath = generateUniqueFilePath(public_path('uploads/posts'), $originalName, 'webp');
                
                $relativePath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $uniqueFullPath);

                convertToWebP($file, $relativePath);

                $post->cover_image = $relativePath;
            }

            if ($request->hasFile('gallery_images')) {
                $existingImages = $post->gallery_images ?? [];

                foreach ($request->file('gallery_images') as $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $uniqueFullPath = generateUniqueFilePath(public_path('uploads/gallery'), $originalName, 'webp');
                    
                    $relativePath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $uniqueFullPath);
                    
                    convertToWebP($file, $relativePath);

                    $existingImages[] = $relativePath;
                }

                $post->gallery_images = $existingImages;
            }

            $post->save();

            foreach ($request->translations as $lang => $data) {
                if (!empty($data['title'])) {
                    $post->translations()->create([
                        'language_slug' => $lang,
                        'title' => $data['title'],
                        'slug' => $data['slug'],
                        'short_description' => $data['short_description'] ?? '',
                        'content' => $data['content'] ?? '',
                        'seo_title' => $data['seo_title'] ?? null,
                        'seo_description' => $data['seo_description'] ?? null,
                        'seo_keywords' => $data['seo_keywords'] ?? null,
                    ]);
                }
            }

            if ($request->tags) {
                $post->tags()->sync($request->tags);
            }

            DB::commit();
            return redirect()->route('posts.index')->with('success', 'Blog post created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        DB::beginTransaction();

        try {
            $post->category_id = $request->category_id;
            $post->order = $request->order ?? 0;
            $post->is_featured = $request->boolean('is_featured');
            $post->comment_enabled = $request->boolean('comment_enabled');
            $post->status = $request->status ?? 'draft';

            if ($request->hasFile('cover_image')) {
                if ($post->cover_image && file_exists(public_path($post->cover_image))) {
                    @unlink(public_path($post->cover_image));
                }

                $file = $request->file('cover_image');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $uniqueFullPath = generateUniqueFilePath(public_path('uploads/posts'), $originalName, 'webp');
                $relativePath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $uniqueFullPath);
                convertToWebP($file, $relativePath);

                $post->cover_image = $relativePath;
            }

            if ($request->hasFile('gallery_images')) {
                $existingImages = $post->gallery_images ?? [];

                foreach ($request->file('gallery_images') as $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $uniqueFullPath = generateUniqueFilePath(public_path('uploads/gallery'), $originalName, 'webp');
                    $relativePath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $uniqueFullPath);
                    convertToWebP($file, $relativePath);
                    $existingImages[] = $relativePath;
                }

                $post->gallery_images = $existingImages;
            }

            $post->save();

            $defaultLangSlug = Language::where('is_default', 1)->value('slug');

            foreach ($request->translations as $lang => $data) {
                if (!empty($data['title'])) {
                    $post->translations()->updateOrCreate(
                        ['language_slug' => $lang],
                        [
                            'title' => $data['title'],
                            'slug' => $data['slug'],
                            'short_description' => $data['short_description'] ?? '',
                            'content' => $data['content'] ?? '',
                            'seo_title' => $data['seo_title'] ?? null,
                            'seo_description' => $data['seo_description'] ?? null,
                            'seo_keywords' => $data['seo_keywords'] ?? null,
                        ]
                    );
                } 
                elseif ($lang !== $defaultLangSlug) {
                    $post->translations()->where('language_slug', $lang)->delete();
                }
            }

            $post->tags()->sync($request->tags ?? []);

            DB::commit();

            return redirect()->route('posts.index')->with('success', 'Blog post updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $post = Post::with(['translations', 'category.translations', 'tags.translations'])->findOrFail($id);
        $languages = Language::all();
        $categories = Category::with('translations')->get();
        $tags = Tag::with('translations')->get();

        return view('admin.posts.edit', compact('post', 'languages', 'categories', 'tags'));
    }

    public function editLang($id, $language)
    {
        $post = Post::with(['translations', 'category.translations', 'tags.translations'])->findOrFail($id);
        $languages = Language::all();
        $categories = Category::with('translations')->get();
        $tags = Tag::with('translations')->get();
        $language = Language::where('slug', $language)->first();

        return view('admin.posts.edit', compact('post', 'languages', 'categories', 'tags', 'language'));
    }

    public function updateStatus(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->status = $request->status;
        $post->save();

        return redirect()->back()->with('success', 'Status updated successfully');
    }

    public function removeGalleryImage(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $imagePath = $request->input('image');

        if ($imagePath && file_exists(public_path($imagePath))) {
            @unlink(public_path($imagePath));
        }

        $galleryImages = $post->gallery_images ?? [];
        $galleryImages = array_filter($galleryImages, function ($img) use ($imagePath) {
            return $img !== $imagePath;
        });

        $post->gallery_images = array_values($galleryImages);
        $post->save();

        return response()->json(['success' => true]);
    }

    public function saveGalleryOrder(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $order = $request->input('order', []);

        if (!empty($order)) {
            $currentImages = $post->gallery_images ?? [];
            $sortedImages = array_filter($order, function ($img) use ($currentImages) {
                return in_array($img, $currentImages);
            });

            if (json_encode($currentImages) !== json_encode($sortedImages)) {
                $post->gallery_images = $sortedImages;
                $post->save();
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    private function deletePostAndAssets(Post $post): void
    {
        $post->tags()->detach();

        if ($post->cover_image && file_exists(public_path($post->cover_image))) {
            @unlink(public_path($post->cover_image));
        }

        if ($post->gallery_images) {
            foreach ($post->gallery_images as $img) {
                if (file_exists(public_path($img))) {
                    @unlink(public_path($img));
                }
            }
        }

        $post->delete();
    }

    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->translations()->delete();
            $this->deletePostAndAssets($post);
            return redirect()->route('posts.index')->with('success', 'Blog post deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    public function destroyTranslation($postId, $translationId)
    {
        DB::beginTransaction();
        try {
            $translation = PostTranslation::findOrFail($translationId);
            $post = $translation->post; 

            $translation->delete();

            $remainingTranslations = $post->translations()->count();

            if ($remainingTranslations === 0) {
                $this->deletePostAndAssets($post);
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'The blog post was also deleted because the last translation was removed.',
                    'redirect' => route('posts.index')
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Translation deleted successfully.',
                'reload' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting translation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the translation: ' . $e->getMessage()
            ], 500);
        }
    }
}
