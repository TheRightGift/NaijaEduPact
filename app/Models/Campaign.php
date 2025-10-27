<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'title',
        'slug',
        'description',
        'goal_amount',
        'type',
        'status',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the university that owns the campaign.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get all of the projects for the campaign.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get all of the challenges for the campaign.
     */
    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }
}