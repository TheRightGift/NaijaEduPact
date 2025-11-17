<?php
namespace App\Http\Controllers\UniversityAdmin;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\UniversityTimelineUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralFundController extends Controller
{
    /**
     * Show the form for editing the general fund.
     */
    public function edit()
    {
        $university = Auth::user()->university;
        $university->load('timelineUpdates');
        
        return view('admin.university.general_fund.edit', compact('university'));
    }

    /**
     * Update the general fund story.
     */
    public function update(Request $request)
    {
        $request->validate(['general_fund_story' => 'nullable|string']);
        
        $university = Auth::user()->university;
        $university->general_fund_story = $request->general_fund_story;
        $university->save();

        return back()->with('success', 'General Fund story updated successfully.');
    }

    /**
     * Store a new timeline update for the general fund.
     */
    public function storeUpdate(Request $request)
    {
        $university = Auth::user()->university;

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'media_type' => 'nullable|string|in:image,video_embed',
            'media_image' => 'nullable|required_if:media_type,image|image|mimes:jpeg,png,jpg|max:2048',
            'media_video' => 'nullable|required_if:media_type,video_embed|url'
        ]);

        $mediaUrl = null;
        if ($request->media_type == 'image') {
            $mediaUrl = $request->file('media_image')->store('timeline-updates', 'public');
        } elseif ($request->media_type == 'video_embed') {
            $mediaUrl = str_replace('watch?v=', 'embed/', $request->media_video);
        }

        UniversityTimelineUpdate::create([
            'university_id' => $university->id,
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'media_type' => $request->input('media_type'),
            'media_url' => $mediaUrl,
        ]);

        return back()->with('success', 'Timeline update posted successfully!');
    }
}