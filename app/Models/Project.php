<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'campaign_id',
        'user_id',
        'title',
        'slug',
        'description',
        'goal_amount',
        'current_amount',
        'status',
        'cover_image_path',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
    ];

    /**
     * Get the university that owns the project.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the user (admin) who created the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the donations for the project.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the updates for the project.
     */
    // public function updates()
    // {
    //     return $this->hasMany(ProjectUpdate::class);
    // }

    /**
     * Get all of the timeline updates for the project.
     */
    public function timelineUpdates()
    {
        return $this->hasMany(ProjectTimelineUpdate::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the campaign that this project belongs to (if any).
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
