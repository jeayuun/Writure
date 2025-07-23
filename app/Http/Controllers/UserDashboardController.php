<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $posts = $user->posts()->latest()->paginate(9);

        return view('user.dashboard', compact('user', 'posts'));
    }
}
