<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\UserActivityLogged;

class ProjectController extends Controller
{
    /**
     * Display the public project discovery page.
     */
    public function index()
    {
        return view('projects.index');
    }

    /**
     * Display the specified project and log the user activity.
     */
    public function show(Project $project)
    {
        // Fire the event *if* a user is logged in
        if (Auth::check()) {
            $details = ['project_id' => $project->id, 'project_title' => $project->title];
            
            // Fire the event
            event(new UserActivityLogged(Auth::user(), 'view_project', $details));
        }
        
        // We'll create a simple view for this test
        return view('projects.show', compact('project'));
    }
}