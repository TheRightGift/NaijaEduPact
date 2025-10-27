<?php

namespace App\Http\Controllers;

use App\Models\Mentorship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorshipController extends Controller
{
    /**
     * Display the user's mentorship dashboard.
     * Shows incoming requests for mentors (alumni).
     * Shows sent requests for mentees (students).
     */
    public function index()
    {
        $user = Auth::user();
        $incomingRequests = [];
        $sentRequests = [];

        // If user is an Alumnus (mentor), fetch their incoming requests
        if ($user->role == 'donor') { 
            $incomingRequests = Mentorship::where('mentor_id', $user->id)
                                          ->with('mentee') // Eager load mentee details
                                          ->where('status', 'pending')
                                          ->latest()
                                          ->get();
        }

        // If user is a Student (mentee), fetch their sent requests
        if ($user->role == 'student') { 
            $sentRequests = Mentorship::where('mentee_id', $user->id)
                                      ->with('mentor') // Eager load mentor details
                                      ->latest()
                                      ->get();
        }

        return view('mentorship.index', compact('user', 'incomingRequests', 'sentRequests'));
    }

    /**
     * Update the user's mentorship profile (availability and bio).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Only alumni can be mentors
        if ($user->role !== 'donor') {
            return back()->with('error', 'Only alumni can be mentors.');
        }

        $request->validate([
            'mentoring_bio' => 'nullable|string|max:1000',
        ]);

        $user->is_available_for_mentoring = $request->has('is_available_for_mentoring');
        $user->mentoring_bio = $request->mentoring_bio;
        $user->save();

        return back()->with('success', 'Mentorship profile updated.');
    }

    /**
     * Respond to a mentorship request (Accept/Decline).
     */
    public function respondToRequest(Request $request, Mentorship $mentorship)
    {
        // Authorize: Make sure the auth user is the intended mentor
        if (Auth::id() !== $mentorship->mentor_id) {
            abort(403);
        }

        $request->validate([
            'action' => 'required|string|in:accept,decline',
        ]);

        if ($request->action == 'accept') {
            $mentorship->status = 'active';
            $mentorship->save();
            return back()->with('success', 'Mentorship request accepted.');
        }

        if ($request->action == 'decline') {
            $mentorship->status = 'declined';
            $mentorship->save();
            return back()->with('success', 'Mentorship request declined.');
        }
    }


    /**
     * Store a new mentorship request sent by a student (mentee).
     */
    public function store(Request $request)
    {
        $mentee = Auth::user();

        // Ensure only students can send requests
        if ($mentee->role !== 'student') {
            return back()->with('error', 'Only students can request mentorship.');
        }

        $request->validate([
            'mentor_id' => 'required|exists:users,id',
        ]);

        // Check if a request already exists
        $existing = Mentorship::where('mentor_id', $request->mentor_id)
                              ->where('mentee_id', $mentee->id)
                              ->whereIn('status', ['pending', 'active'])
                              ->exists();

        if ($existing) {
            return back()->with('error', 'You already have an active or pending request with this mentor.');
        }

        // Create the new mentorship record
        Mentorship::create([
            'mentor_id' => $request->mentor_id,
            'mentee_id' => $mentee->id,
            'status' => 'pending',
        ]);

        return redirect()->route('mentorship.index')->with('success', 'Mentorship request sent successfully.');
    }
}