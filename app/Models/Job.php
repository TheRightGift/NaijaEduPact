<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_postings';
    
    protected $fillable = ['user_id', 'university_id', 'title', 'description', 'job_type', 'location', 'status'];

    public function user() { return $this->belongsTo(User::class); }
    public function university() { return $this->belongsTo(University::class); }
}
