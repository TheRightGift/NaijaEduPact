<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\Project;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function show(University $university)
    {
        $projects = Project::where('university_id', $university->id)
                           ->where('status', 'active')
                           ->latest()
                           ->get();
                           
        return view('public.universities.show', compact('university', 'projects'));
    }
}