<?php

namespace App\Http\Controllers\UniversityAdmin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    /**
     * Display a listing of the university's campaigns.
     */
    public function index()
    {
        // This assumes the admin user has a 'university_id' relationship or field.
        $universityId = Auth::user()->university_id; 
        
        $campaigns = Campaign::where('university_id', $universityId)
                             ->latest()
                             ->paginate(10);

        return view('admin.university.campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create()
    {
        return view('admin.university.campaigns.create');
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal_amount' => 'required|numeric|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $universityId = Auth::user()->university_id;

        Campaign::create([
            'university_id' => $universityId,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'draft',
        ]);

        return redirect()->route('uadmin.campaigns.index')->with('success', 'Campaign created successfully! You can now add projects and challenges.');
    }

    /**
     * Display the specified campaign.
     */
    public function show(Campaign $campaign)
    {
        // Authorization check: Ensure the admin can only view their own university's campaigns.
        if ($campaign->university_id !== Auth::user()->university_id) {
            abort(403);
        }

        // Eager load related projects and challenges for efficiency
        $campaign->load('projects', 'challenges');

        return view('admin.university.campaigns.show', compact('campaign'));
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit(Campaign $campaign)
    {
        if ($campaign->university_id !== Auth::user()->university_id) {
            abort(403);
        }

        return view('admin.university.campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        if ($campaign->university_id !== Auth::user()->university_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal_amount' => 'required|numeric|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => ['required', Rule::in(['draft', 'active', 'completed'])],
        ]);

        $campaign->update($request->all());

        return redirect()->route('uadmin.campaigns.show', $campaign)->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(Campaign $campaign)
    {
        if ($campaign->university_id !== Auth::user()->university_id) {
            abort(403);
        }

        // Note: Associated projects will have their campaign_id set to null due to the migration's onDelete('set null') constraint.
        $campaign->delete();

        return redirect()->route('uadmin.campaigns.index')->with('success', 'Campaign deleted successfully.');
    }
}