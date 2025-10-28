<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;

class LandingPageController extends Controller
{
    /**
     * Display the landing page with featured projects.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch the 3 most recent active projects to feature on the page
        $featuredProjects = Project::where('status', 'active')
                                   ->latest()
                                   ->take(3)
                                   ->get();

        return view('landing', compact('featuredProjects'));
    }
}
