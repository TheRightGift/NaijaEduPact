<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UniversityAdminController extends Controller
{
    public function dashboard()
    {
        // You would fetch projects related to this admin's university
        return view('admin.university.dashboard');
    }

    public function createProject()
    {
        return view('admin.university.create_project');
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:1',
            // 'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // This assumes you have a way to link the admin to their university.
        // For now, we'll hardcode a university_id for the example.
        // In a real app, you'd get this from the authenticated user's relationship.
        
        Project::create([
            'university_id' => 1, // Replace with Auth::user()->university_id
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'status' => 'pending', // Projects must be approved by Super Admin
        ]);

        return redirect()->route('uadmin.dashboard')->with('success', 'Project submitted for approval!');
    }
}
