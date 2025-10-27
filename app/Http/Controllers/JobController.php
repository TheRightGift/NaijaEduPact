<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of the authenticated user's job postings.
     */
    public function index()
    {
        $jobs = Job::where('user_id', Auth::id())->latest()->get();
        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        // Authorize this action using the JobPolicy
        $this->authorize('create', Job::class); 
        return view('jobs.create');
    }

    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Job::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'job_type' => 'required|string|in:full-time,part-time,internship',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Assuming the user is associated with one university
        $universityId = Auth::user()->university_id; 
        if (!$universityId) {
            return back()->with('error', 'You must be associated with a university to post a job.');
        }

        // Create the job directly and set the authenticated user's id instead of relying on a missing relationship.
        Job::create([
            'user_id' => Auth::id(),
            'university_id' => $universityId,
            'title' => $request->title,
            'job_type' => $request->job_type,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->route('jobs.index')->with('success', 'Job posted successfully.');
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(Job $job)
    {
        // Authorize this action using the JobPolicy
        $this->authorize('update', $job);
        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified job in storage.
     */
    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        $request->validate([
            'title' => 'required|string|max:255',
            'job_type' => 'required|string|in:full-time,part-time,internship',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:active,closed',
        ]);

        $job->update($request->all());

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified job from storage.
     */
    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }
}
