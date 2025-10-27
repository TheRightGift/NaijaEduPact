<?php

namespace App\Http\Controllers\UniversityAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $query = User::join('donor_scores', 'users.id', '=', 'donor_scores.user_id')
                    // ->where('users.university_id', auth()->user()->university_id) // Add this when ready
                    ->select('users.name', 'users.email', 'donor_scores.*');

        // Allow admin to filter by segment
        if ($request->has('segment')) {
            $query->where('donor_scores.segment', $request->segment);
        }

        $donors = $query->orderBy('likelihood_score', 'desc')->paginate(25);

        return view('admin.university.analytics.index', compact('donors'));
    }
}
