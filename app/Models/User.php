<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'expected_graduation_year',
        'is_available_for_mentoring',
        'mentoring_bio',
        'university_id', // Assuming you've added this for linking users to universities
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_available_for_mentoring' => 'boolean',
        ];
    }

    /**
     * Get the university that the user belongs to.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the donations made by the user.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
    /**
     * Get the activities logged for the user.
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    /**
     * Get the donor score for the user.
     */
    public function donorScore()
    {
        return $this->hasOne(DonorScore::class);
    }

    /**
     * Get the job postings made by the user (alumnus).
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get the mentorships where this user is a MENTOR.
     */
    public function mentorshipsAsMentor()
    {
        return $this->hasMany(Mentorship::class, 'mentor_id');
    }

    /**
     * Get the mentorships where this user is a mentee.
     */
    public function mentorshipsAsMentee()
    {
        return $this->hasMany(Mentorship::class, 'mentee_id');
    }
}
