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
            // ... (student logic)
            return view('dashboards.student', compact('jobs', 'mentors'));
        }
        if ($user->role == 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        }
        if ($user->role == 'universityadmin') {
            return redirect()->route('uadmin.dashboard');
        }

        // --- New Donor Dashboard Logic ---

        // A. Get all active projects from the user's university
        $universityProjects = Project::where('university_id', $user->university_id)
                                     ->where('status', 'active')
                                     ->latest()
                                     ->get();

        // B. Get a list of project IDs and the total amount this user has donated to each
        $userDonationsPerProject = Donation::where('user_id', $user->id)
                                     ->where('status', 'successful')
                                     ->groupBy('project_id')
                                     // Create a sum of all donations for each project
                                     ->selectRaw('project_id, SUM(amount) as total_amount')
                                     // Create an associative array: [project_id => total_amount]
                                     ->pluck('total_amount', 'project_id')
                                     ->all();

        // C. Get "at-a-glance" impact stats
        $totalDonations = array_sum($userDonationsPerProject); // Sum of the new array
        $projectsSupported = count($userDonationsPerProject); // Count of the new array

        // D. Pass all data to the 'home' view
        return view('home', compact(
            'universityProjects', 
            'userDonationsPerProject', // <-- Pass the new array
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
