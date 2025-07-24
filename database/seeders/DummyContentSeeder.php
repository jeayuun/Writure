<?php

namespace Database\Seeders;

use App\Models\User; 
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
        Storage::disk('public')->deleteDirectory('uploads/posts');
        Storage::disk('public')->makeDirectory('profile-photos');
        Storage::disk('public')->makeDirectory('uploads/posts');

        // --- 1. Languages ---
        DB::table('languages')->insert([
            ['id' => 1, 'name' => 'English', 'slug' => 'en', 'flag' => 'gb', 'status' => 1, 'is_default' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'name' => 'Filipino', 'slug' => 'ph', 'flag' => 'ph', 'status' => 1, 'is_default' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'name' => 'Русский', 'slug' => 'ru', 'flag' => 'ru', 'status' => 1, 'is_default' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'name' => 'Français', 'slug' => 'fr', 'flag' => 'fr', 'status' => 1, 'is_default' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // --- 2. Users ---
        $adminPhotoPath = null;
        $userPhotoPath = null;

        $adminSourceImagePath = public_path('profile-photos/me.png');
        if (file_exists($adminSourceImagePath)) {
            $adminPhotoPath = Storage::disk('public')->putFile('profile-photos', new File($adminSourceImagePath));
        }
        $userSourceImagePath = public_path('profile-photos/jeayuun.jpg');
        if (file_exists($userSourceImagePath)) {
            $userPhotoPath = Storage::disk('public')->putFile('profile-photos', new File($userSourceImagePath));
        }

        $usersData = [
            [
                'id' => 1,
                'name' => 'Admin User',
                'username' => 'admin', 
                'email' => 'admin@writure.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'profile_photo_path' => $adminPhotoPath,
            ],
            [
                'id' => 2,
                'name' => 'Jedidiah Villegas',
                'username' => 'jeayuun', 
                'email' => 'villegasjedidiah@gmail.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'profile_photo_path' => $userPhotoPath, 
            ]
        ];
        
        foreach ($usersData as $userData) {
            User::create([
                'id' => $userData['id'],
                'name' => $userData['name'],
                'username' => $userData['username'],
                'email' => $userData['email'],
                'email_verified_at' => Carbon::now(),
                'password' => $userData['password'],
                'is_admin' => $userData['is_admin'],
                'profile_photo_path' => $userData['profile_photo_path'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $authors = collect($usersData)->keyBy('id')->map(fn ($user) => $user['name']);

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
            ['id' => 1, 'user_id' => 1, 'category_id' => 1, 'title' => 'Mastering Laravel for Modern Web Apps', 'tags' => [1], 'desc' => 'Dive deep into the Laravel ecosystem and learn how to build scalable, high-performance web applications with its elegant syntax and robust features.', 'content' => '<p>Laravel has become the go-to PHP framework for developers who want to build everything from simple websites to complex enterprise-level applications. This post explores advanced concepts like the service container, middleware, and query optimization to help you write cleaner and more efficient code. We will also touch upon Laravel Octane for boosting performance.</p>'],
            ['id' => 2, 'user_id' => 2, 'category_id' => 2, 'title' => 'The Rise of Generative AI', 'tags' => [3], 'desc' => 'Explore the transformative impact of generative AI models like GPT-4, and understand the technology that powers them.', 'content' => '<p>Generative AI is not just a buzzword; it\'s a technological revolution. From creating stunning artwork to writing human-like text, these models are changing industries. We will break down how large language models (LLMs) work, discuss their ethical implications, and look at real-world applications that are already making a difference.</p>'],
            ['id' => 3, 'user_id' => 1, 'category_id' => 3, 'title' => 'Top 5 Cybersecurity Threats in 2025', 'tags' => [4], 'desc' => 'Stay ahead of the curve by understanding the most significant cybersecurity threats that organizations and individuals will face in the near future.', 'content' => '<p>As technology evolves, so do the methods of cybercriminals. In this article, we identify and analyze the top five emerging threats, including AI-powered phishing attacks, ransomware-as-a-service (RaaS), and vulnerabilities in IoT devices. Learn how to protect your digital assets and what to watch out for.</p>'],
            ['id' => 4, 'user_id' => 2, 'category_id' => 1, 'title' => 'React vs. Vue: A Developer\'s Dilemma', 'tags' => [2], 'desc' => 'A comprehensive comparison between two of the most popular JavaScript frameworks. Which one is right for your next project?', 'content' => '<p>Choosing a frontend framework is a critical decision. This post provides a side-by-side comparison of React and Vue, looking at factors like learning curve, performance, ecosystem, and community support. We will build a small sample app in both frameworks to highlight their differences and similarities in a practical way.</p>'],
            ['id' => 5, 'user_id' => 1, 'category_id' => 2, 'title' => 'Building a Recommendation Engine from Scratch', 'tags' => [3], 'desc' => 'Learn the fundamentals of recommendation systems by building a simple yet effective engine using Python and collaborative filtering.', 'content' => '<p>Ever wondered how Netflix or Amazon know what you want to see next? This tutorial demystifies recommendation engines. We will walk through the process of implementing a collaborative filtering algorithm, a technique that makes predictions based on the behavior of other users, using the popular Python library, Pandas.</p>'],
            ['id' => 6, 'user_id' => 1, 'category_id' => 1, 'title' => 'The Power of Serverless Architecture', 'tags' => [1], 'desc' => 'Discover how serverless computing with services like AWS Lambda can reduce costs, simplify deployment, and improve scalability.', 'content' => '<p>Forget managing servers. Serverless architecture allows you to run code without provisioning or managing any infrastructure. This post explains the "Function as a Service" (FaaS) model, explores its pros and cons, and provides a practical example of deploying a serverless API with Laravel Vapor.</p>'],
            ['id' => 7, 'user_id' => 2, 'category_id' => 3, 'title' => 'Understanding Zero-Trust Security Models', 'tags' => [4], 'desc' => 'Move beyond the traditional castle-and-moat security approach. Learn why "never trust, always verify" is the future of digital protection.', 'content' => '<p>In a world of remote work and cloud services, the traditional network perimeter is disappearing. A Zero-Trust model assumes that threats can exist both inside and outside the network. This article explains the core principles of Zero-Trust, including micro-segmentation and multi-factor authentication, and how to implement them.</p>'],
            ['id' => 8, 'user_id' => 1, 'category_id' => 1, 'title' => 'GraphQL vs. REST: The API Battle', 'tags' => [1, 2], 'desc' => 'A deep dive into the pros and cons of GraphQL and REST. Understand which API architecture is better suited for your application\'s needs.', 'content' => '<p>For decades, REST has been the standard for API design. But GraphQL offers a more flexible and efficient alternative. We will compare them on key aspects like data fetching, performance, and developer experience. See practical query examples and learn when to choose one over the other.</p>'],
            ['id' => 9, 'user_id' => 2, 'category_id' => 2, 'title' => 'Natural Language Processing Explained', 'tags' => [3], 'desc' => 'A beginner-friendly guide to Natural Language Processing (NLP), the technology that allows computers to understand and interpret human language.', 'content' => '<p>From spam filters to virtual assistants like Siri, NLP is everywhere. This post breaks down core NLP concepts such as tokenization, sentiment analysis, and named entity recognition. We\'ll explore how these techniques are used to extract meaningful information from text data.</p>'],
            ['id' => 10, 'user_id' => 1, 'category_id' => 1, 'title' => 'The Importance of CI/CD in Modern Development', 'tags' => [1], 'desc' => 'Automate your build, test, and deployment pipeline with Continuous Integration and Continuous Deployment (CI/CD) to ship better software, faster.', 'content' => '<p>CI/CD is a cornerstone of modern DevOps practices. By automating the software delivery process, teams can reduce manual errors and increase deployment frequency. We will discuss the benefits, outline a typical CI/CD workflow, and introduce popular tools like GitHub Actions and Jenkins.</p>'],
            ['id' => 11, 'user_id' => 2, 'category_id' => 3, 'title' => 'Ethical Hacking: An Introduction', 'tags' => [4], 'desc' => 'Think like a hacker to defend like a pro. This article introduces the world of ethical hacking and penetration testing.', 'content' => '<p>Ethical hackers, or "white hats," are cybersecurity professionals who use their skills to find and fix security vulnerabilities before malicious attackers can exploit them. Learn about the different phases of a penetration test, from reconnaissance to covering tracks, and the tools of the trade.</p>'],
            ['id' => 12, 'user_id' => 1, 'category_id' => 2, 'title' => 'Computer Vision with OpenCV and Python', 'tags' => [3], 'desc' => 'Unlock the power of image and video analysis. Learn how to perform tasks like object detection and image filtering using OpenCV.', 'content' => '<p>Computer vision is one of the most exciting fields in AI. This hands-on tutorial will guide you through the basics of using the OpenCV library in Python to manipulate images, detect faces, and track objects in a video stream. No prior experience in computer vision is required.</p>'],
            ['id' => 13, 'user_id' => 2, 'category_id' => 1, 'title' => 'WebAssembly: The Future of Web Performance', 'tags' => [2], 'desc' => 'Go beyond JavaScript and run high-performance, near-native code directly in the browser with WebAssembly (Wasm).', 'content' => '<p>WebAssembly is a new type of code that can be run in modern web browsers, offering a significant performance boost for computationally intensive tasks. This post explains what Wasm is, how it works alongside JavaScript, and its potential to enable a new class of powerful web applications, from games to video editing.</p>'],
            ['id' => 14, 'user_id' => 1, 'category_id' => 3, 'title' => 'Securing APIs with OAuth 2.0', 'tags' => [4], 'desc' => 'Learn how to implement OAuth 2.0, the industry-standard protocol for authorization, to secure your APIs and protect user data.', 'content' => '<p>When an application needs to access data from another service on behalf of a user, OAuth 2.0 is the answer. This article breaks down the different OAuth 2.0 grant types (like Authorization Code and Client Credentials) and explains how the flow works to provide secure, delegated access without sharing passwords.</p>'],
            ['id' => 15, 'user_id' => 2, 'category_id' => 1, 'title' => 'Getting Started with Docker and Containers', 'tags' => [1], 'desc' => 'Simplify your development workflow and eliminate "it works on my machine" issues by packaging your applications into lightweight, portable containers.', 'content' => '<p>Docker has revolutionized how developers build, ship, and run applications. This beginner\'s guide explains the core concepts of containers and images and walks you through writing your first Dockerfile. Learn how to containerize a simple web application and ensure it runs consistently across any environment.</p>'],
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
                'short_description' => $postData['desc'],
                'content' => $postData['content'],
                'author' => $authors[$postData['user_id']], 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            foreach ($postData['tags'] as $tagId) {
                DB::table('post_tag')->insert(['post_id' => $postData['id'], 'tag_id' => $tagId]);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}