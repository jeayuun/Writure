<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPosts = Post::count();
        $totalCategories = Category::count();
        $totalTags = Tag::count();
        $totalUsers = User::count(); 

        $recentPosts = Post::with('translations')
            ->latest('updated_at')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalPosts',
            'totalCategories',
            'totalTags',
            'totalUsers', 
            'recentPosts'
        ));
    }
}
