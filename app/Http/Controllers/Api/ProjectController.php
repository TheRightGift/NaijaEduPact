<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Return a list of all active projects.
     */
    public function index()
    {
        $projects = Project::where('status', 'active')
                           ->with('university:id,name,slug') // Eager load university with selected fields
                           ->latest()
                           ->get();

        return response()->json($projects);
    }
    
    /**
     * Show details of a specific project.
     */
    public function show(Project $project)
    {
        if (Auth::check()) {
            $details = ['project_id' => $project->id, 'project_title' => $project->title];
            
            // Fire the event
            event(new \App\Events\UserActivityLogged(Auth::user(), 'view_project', $details));
        }
        
        return view('projects.show', compact('project'));
    }
    
}
