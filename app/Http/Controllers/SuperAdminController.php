<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.superadmin.dashboard');
    }

    public function pendingProjects()
    {
        $pendingProjects = Project::where('status', 'pending')->with('university')->get();
        return view('admin.superadmin.pending_projects', compact('pendingProjects'));
    }

    public function approveProject(Project $project)
    {
        $project->update(['status' => 'active']);
        return back()->with('success', 'Project has been approved and is now live.');
    }

    public function rejectProject(Project $project)
    {
        $project->update(['status' => 'rejected']);
        return back()->with('success', 'Project has been rejected.');
    }
}
