<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Job; 
use App\Models\User;

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

        // 1. Check if the user is a Student
        if ($user->role == 'student') {
            
            // Fetch active jobs
            $jobs = Job::where('university_id', $user->university_id)
                       ->where('status', 'active')
                       ->latest()
                       ->get();
                       
            // Fetch available mentors from the same university
            $mentors = User::where('university_id', $user->university_id)
                           ->where('role', 'donor')
                           ->where('is_available_for_mentoring', true)
                           ->get();

            // Return the student dashboard with both jobs and mentors
            return view('dashboards.student', compact('jobs', 'mentors'));
        }

        // 2. Check for Admins and redirect them
        if ($user->role == 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        }
        if ($user->role == 'universityadmin') {
            return redirect()->route('uadmin.dashboard');
        }

        // 3. Fallback for 'donor' (Alumni)
        // This view will contain their dashboard, including the "Manage My Job Postings" button.
        return view('home');
    }
}
