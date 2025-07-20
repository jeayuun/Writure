<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class UserDashboardController extends \App\Http\Controllers\Frontend\BaseFrontendController
{
    /**
     * Display the user's dashboard.
     */
    public function index(): View
    {
        return view('user.dashboard');
    }
}
