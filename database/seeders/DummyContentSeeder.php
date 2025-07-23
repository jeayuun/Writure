<?php

namespace Database\Seeders;

use App\Models\User; // Use the User model
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class DummyContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // To ensure a clean slate, you can disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DB::table('post_tag')->truncate();
        DB::table('post_translations')->truncate();
        DB::table('posts')->truncate();
        DB::table('tag_translations')->truncate();
        DB::table('tags')->truncate();
        DB::table('category_translations')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();
        DB::table('languages')->truncate();
        
        // Clean up storage directories before seeding
        Storage::disk('public')->deleteDirectory('profile-photos');
        Storage::disk('public')->deleteDirectory('post-covers');
        Storage::disk('public')->makeDirectory('profile-photos');
        Storage::disk('public')->makeDirectory('post-covers');

        // --- 1. Languages ---
        DB::table('languages')->insert([
            ['id' => 1, 'name' => 'English', 'slug' => 'en', 'flag' => 'gb', 'status' => 1, 'is_default' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'name' => 'Filipino', 'slug' => 'ph', 'flag' => 'ph', 'status' => 1, 'is_default' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'name' => 'Русский', 'slug' => 'ru', 'flag' => 'ru', 'status' => 1, 'is_default' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'name' => 'Français', 'slug' => 'fr', 'flag' => 'fr', 'status' => 1, 'is_default' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // --- 2. Users ---
        $adminPhotoPath = null;
        $sourceImagePath = public_path('profile-photos/me.png'); 
        if (file_exists($sourceImagePath)) {
            $adminPhotoPath = Storage::disk('public')->putFile('profile-photos', new File($sourceImagePath));
        }

        // Using the User model is more robust
        User::create([
            'id' => 1,
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@wrytte.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password'),
            'is_admin' => true,
            'profile_photo_path' => $adminPhotoPath,
        ]);

        User::create([
            'id' => 2,
            'name' => 'Jane Doe',
            'username' => 'janedoe',
            'email' => 'jane.doe@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password'),
            'is_admin' => false,
            'profile_photo_path' => null,
        ]);

        // --- 3. Categories and Translations ---
        DB::table('categories')->insert([
            ['id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        DB::table('category_translations')->insert([
            ['category_id' => 1, 'language_slug' => 'en', 'name' => 'Web Development', 'slug' => 'web-development', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['category_id' => 2, 'language_slug' => 'en', 'name' => 'Artificial Intelligence', 'slug' => 'artificial-intelligence', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['category_id' => 3, 'language_slug' => 'en', 'name' => 'Cybersecurity', 'slug' => 'cybersecurity', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // --- 4. Tags and Translations ---
        DB::table('tags')->insert([
            ['id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        
        DB::table('tag_translations')->insert([
            ['tag_id' => 1, 'language_slug' => 'en', 'name' => 'Laravel', 'slug' => 'laravel', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['tag_id' => 2, 'language_slug' => 'en', 'name' => 'JavaScript', 'slug' => 'javascript', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['tag_id' => 3, 'language_slug' => 'en', 'name' => 'Machine Learning', 'slug' => 'machine-learning', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['tag_id' => 4, 'language_slug' => 'en', 'name' => 'Security', 'slug' => 'security', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        
        // --- 5. Posts, Translations, and Tags ---
        $posts = [
            ['id' => 1, 'user_id' => 1, 'category_id' => 1, 'title' => 'Mastering Laravel for Modern Web Apps', 'tags' => [1]],
            ['id' => 2, 'user_id' => 2, 'category_id' => 2, 'title' => 'The Rise of Generative AI', 'tags' => [3]],
            ['id' => 3, 'user_id' => 1, 'category_id' => 3, 'title' => 'Top 5 Cybersecurity Threats in 2025', 'tags' => [4]],
            ['id' => 4, 'user_id' => 2, 'category_id' => 1, 'title' => 'React vs. Vue: A Developer Dilemma', 'tags' => [2]],
            ['id' => 5, 'user_id' => 1, 'category_id' => 2, 'title' => 'Building a Recommendation Engine from Scratch', 'tags' => [3]],
            ['id' => 6, 'user_id' => 1, 'category_id' => 1, 'title' => 'The Power of Serverless Architecture', 'tags' => [1]],
            ['id' => 7, 'user_id' => 2, 'category_id' => 3, 'title' => 'Understanding Zero-Trust Security Models', 'tags' => [4]],
            ['id' => 8, 'user_id' => 1, 'category_id' => 1, 'title' => 'GraphQL vs. REST: The API Battle', 'tags' => [1, 2]],
            ['id' => 9, 'user_id' => 2, 'category_id' => 2, 'title' => 'Natural Language Processing Explained', 'tags' => [3]],
            ['id' => 10, 'user_id' => 1, 'category_id' => 1, 'title' => 'The Importance of CI/CD in Modern Development', 'tags' => [1]],
            ['id' => 11, 'user_id' => 2, 'category_id' => 3, 'title' => 'Ethical Hacking: An Introduction', 'tags' => [4]],
            ['id' => 12, 'user_id' => 1, 'category_id' => 2, 'title' => 'Computer Vision with OpenCV and Python', 'tags' => [3]],
            ['id' => 13, 'user_id' => 2, 'category_id' => 1, 'title' => 'WebAssembly: The Future of Web Performance', 'tags' => [2]],
            ['id' => 14, 'user_id' => 1, 'category_id' => 3, 'title' => 'Securing APIs with OAuth 2.0', 'tags' => [4]],
            ['id' => 15, 'user_id' => 2, 'category_id' => 1, 'title' => 'Getting Started with Docker and Containers', 'tags' => [1]],
        ];

        foreach ($posts as $postData) {
            $slug = Str::slug($postData['title']);
            
            DB::table('posts')->insert([
                'id' => $postData['id'],
                'user_id' => $postData['user_id'],
                'category_id' => $postData['category_id'],
                'cover_image' => 'uploads/posts/tech_' . str_pad($postData['id'], 2, '0', STR_PAD_LEFT) . '.webp',
                'status' => 'published',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('post_translations')->insert([
                'post_id' => $postData['id'],
                'language_slug' => 'en',
                'title' => $postData['title'],
                'slug' => $slug,
                'short_description' => 'This is a sample short description for the post titled "' . $postData['title'] . '".',
                'content' => '<p>This is the full content for the post about ' . $postData['title'] . '. It demonstrates how the blog post would be rendered with rich text formatting. You can expand this with more detailed content as needed.</p>',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            foreach ($postData['tags'] as $tagId) {
                DB::table('post_tag')->insert(['post_id' => $postData['id'], 'tag_id' => $tagId]);
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
