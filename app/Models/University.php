<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'logo_path', 'status', 'general_fund_story'
    ];

    /**
     * Get the projects for the university.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get all of the campaigns for the university.
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    // Add this new relationship
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function timelineUpdates()
    {
        return $this->hasMany(UniversityTimelineUpdate::class)->orderBy('created_at', 'desc');
    }
}
