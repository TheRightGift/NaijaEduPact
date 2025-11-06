<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Job; 
use App\Models\User;
use App\Models\Project; 
use App\Models\Donation;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * This method acts as a router, sending users to the correct dashboard
     * based on their role.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();

        // --- Role-based Redirects ---
        if ($user->role == 'student') {
            $jobs = Job::where('university_id', $user->university_id)
                       ->where('status', 'active')->latest()->get();
            $mentors = User::where('university_id', $user->university_id)
                           ->where('role', 'donor')
                           ->where('is_available_for_mentoring', true)->get();
            return view('dashboards.student', compact('jobs', 'mentors'));
        }
        if ($user->role == 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        }
        if ($user->role == 'universityadmin') {
            return redirect()->route('uadmin.dashboard');
        }

        // --- 3. New Donor Dashboard Logic ---
        // This logic now runs only for the 'donor' role

        // A. Get all active projects from the user's university
        $universityProjects = Project::where('university_id', $user->university_id)
                                     ->where('status', 'active')
                                     ->latest()
                                     ->get();

        // B. Get a list of project IDs this user has successfully donated to
        $donatedProjectIds = Donation::where('user_id', $user->id)
                                     ->where('status', 'successful')
                                     ->pluck('project_id')
                                     ->unique()
                                     ->toArray();

        // C. Get "at-a-glance" impact stats
        $totalDonations = Donation::where('user_id', $user->id)
                                  ->where('status', 'successful')
                                  ->sum('amount');
        
        $projectsSupported = count($donatedProjectIds);

        // D. Pass all data to the 'home' view
        return view('home', compact(
            'universityProjects', 
            'donatedProjectIds', 
            'totalDonations', 
            'projectsSupported'
        ));
    }

    /**
     * Show the user's personal donation history.
     */
    public function donationHistory()
    {
        $user = Auth::user();

        // Ensure only donors can see this page
        if ($user->role !== 'donor') {
            abort(403);
        }

        // Fetch all successful donations, eager-load the project info
        $donations = Donation::where('user_id', $user->id)
                             ->where('status', 'successful')
                             ->with('project')
                             ->latest()
                             ->paginate(20);

        return view('donations.history', compact('donations'));
    }
}
