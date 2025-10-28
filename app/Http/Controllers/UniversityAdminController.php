<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UniversityAdminController extends Controller
{
    public function dashboard()
    {
        // Fetch projects for this admin's university
        $projects = Project::where('university_id', Auth::user()->university_id)
                           ->latest()
                           ->get();
                           
        return view('admin.university.dashboard', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     * Renamed from createProject to create
     */
    public function create()
    {
        return view('admin.university.create_project');
    }

    /**
     * Store a newly created project in storage.
     * Renamed from storeProject to store
     */
    public function store(Request $request)
    {
        // 2. Add validation for the cover image
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:1',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // 2MB Max
        ]);

        $imagePath = null;

        // 3. Check if a file was uploaded and store it
        if ($request->hasFile('cover_image')) {
            // Store in 'storage/app/public/project-covers'
            $path = $request->file('cover_image')->store('project-covers', 'public');
            $imagePath = Storage::url('public/'.$path);
        }

        Project::create([
            'university_id' => Auth::user()->university_id, // Make sure user has university_id
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'cover_image_path' => $imagePath, // 4. Save the path to the database
            'status' => 'pending',
        ]);

        return redirect()->route('uadmin.dashboard')->with('success', 'Project submitted for approval!');
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        // Authorization: Ensure admin can only edit their own university's projects
        if ($project->university_id !== Auth::user()->university_id) {
            abort(403);
        }
        return view('admin.university.edit_project', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        if ($project->university_id !== Auth::user()->university_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:1',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imagePath = $project->cover_image_path;

        // Check if a new image is being uploaded
        if ($request->hasFile('cover_image')) {
            // Delete the old image from storage
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            // Store the new image
            $imagePath = $request->file('cover_image')->store('project-covers', 'public');
        }

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'cover_image_path' => $imagePath,
        ]);

        return redirect()->route('uadmin.dashboard')->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->university_id !== Auth::user()->university_id) {
            abort(403);
        }

        // Delete the associated image from storage
        if ($project->cover_image_path) {
            Storage::disk('public')->delete($project->cover_image_path);
        }

        $project->delete();

        return redirect()->route('uadmin.dashboard')->with('success', 'Project deleted.');
    }
}
