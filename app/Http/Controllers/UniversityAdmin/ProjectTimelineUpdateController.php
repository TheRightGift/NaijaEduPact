<?php
namespace App\Http\Controllers\UniversityAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTimelineUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectTimelineUpdateController extends Controller
{
    public function store(Request $request, Project $project)
    {
        // Ensure admin owns this project
        if ($project->university_id !== Auth::user()->university_id) {
            abort(403);
        }

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
            // Basic logic to get an embeddable YouTube URL
            $mediaUrl = str_replace('watch?v=', 'embed/', $request->media_video);
        }

        ProjectTimelineUpdate::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'media_type' => $request->media_type,
            'media_url' => $mediaUrl,
        ]);

        return back()->with('success', 'Project update posted successfully!');
    }
}