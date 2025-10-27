<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPersonalizedFollowup implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(\App\Events\UserActivityLogged $event): void
    {
        // Example: Check if the activity was 'click_scholarship_story'
        if ($event->activityType == 'click_scholarship_story') {
            // Send a follow-up email in 7 days
            Mail::to($event->user)
                 ->later(now()->addDays(7), new \App\Mail\ScholarshipImpactReport($event->user));
        }
    }
}
