<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display the public-facing campaign page.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\View\View
     */
    public function show(Campaign $campaign)
    {
        // Eager load the relationships needed for the page
        $campaign->load('projects', 'challenges');

        // Pass the campaign data to the Blade view
        return view('public.campaigns.show', compact('campaign'));
    }
}