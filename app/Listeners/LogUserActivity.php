<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserActivityLogged;
use App\Models\UserActivity;
use Illuminate\Events\Attributes\ShouldListen;

class LogUserActivity
{
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
    #[ShouldListen(UserActivityLogged::class)] // <-- 2. Add the attribute
    public function handle(UserActivityLogged $event): void
    {
        UserActivity::create([
            'user_id' => $event->user->id,
            'activity_type' => $event->activityType,
            'details' => json_encode($event->details),
        ]);
    }
}
