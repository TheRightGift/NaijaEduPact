<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'donor_name',
        'match_amount',
        'challenge_type',
        'challenge_threshold',
        'status',
    ];

    protected $casts = [
        'match_amount' => 'decimal:2',
    ];

    /**
     * Get the campaign that owns the challenge.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}