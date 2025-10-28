<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorScore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'likelihood_score',
        'capacity_score',
        'segment',
    ];

    /**
     * Get the user that this score belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
