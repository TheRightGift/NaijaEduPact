<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityTimelineUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id', 
        'user_id', 
        'title', 
        'content', 
        'media_type', 
        'media_url'
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}