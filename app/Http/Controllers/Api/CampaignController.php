<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Provide live statistics for a public campaign page.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Campaign $campaign)
    {
        // Get the IDs of all projects associated with this campaign
        $projectIds = $campaign->projects()->pluck('id');

        // Calculate the total amount raised across all associated projects
        $totalRaised = Donation::whereIn('project_id', $projectIds)
                               ->where('status', 'successful')
                               ->sum('amount');

        // Count the number of unique donors across all associated projects
        $donorCount = Donation::whereIn('project_id', $projectIds)
                              ->where('status', 'successful')
                              ->distinct('user_id')
                              ->count('user_id');

        return response()->json([
            'total_raised' => $totalRaised,
            'donor_count' => $donorCount,
        ]);
    }
}