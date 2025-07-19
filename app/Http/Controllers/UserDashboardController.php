<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function index(): View
    {
        return view('user.dashboard');
    }
}
