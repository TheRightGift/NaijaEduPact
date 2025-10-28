<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentorship extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'status',
    ];

    /**
     * Get the mentor (the user who is an alumnus).
     */
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Get the mentee (the user who is a student).
     */
    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
