<?php

namespace App\Http\Controllers;

use App\Models\Course;

class LandingController extends Controller
{
    public function index()
    {
        $homepageCourses = Course::where('admin_status', 'approved')
            ->where('status', 'published')
            ->latest()
            ->take(10)
            ->get();

        // use your landing view file name here
        return view('landing', compact('homepageCourses'));
    }
}
