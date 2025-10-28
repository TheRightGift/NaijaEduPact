<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\DonorScore;

class CalculateDonorScores extends Command
{
    protected $signature = 'analytics:calculate-scores';
    protected $description = 'Calculate likelihood and capacity scores for all donors.';

    public function handle()
    {
        // 1. FIX: Only score 'donor' and 'student' roles
        $rolesToScore = ['donor', 'student'];

        User::whereIn('role', $rolesToScore)
            ->with('donations', 'activities')
            ->chunk(100, function ($users) {
            foreach ($users as $user) {
                $likelihood = 0;
                
                // 1. Likelihood Score (0-100)
                if ($user->last_login_at && $user->last_login_at->gt(now()->subMonths(3))) $likelihood += 20;
                if ($user->activities->where('activity_type', 'view_project')->count() > 5) $likelihood += 30;
                if ($user->donations->count() > 0) $likelihood += 50;
                $likelihood = min($likelihood, 100);

                // 2. Capacity Score (0-100)
                $capacity = 0;
                $avgDonation = $user->donations->avg('amount');
                
                // 2. FIX: Changed > to >= to include 50,000
                if ($avgDonation >= 50000) $capacity = 100;
                elseif ($avgDonation > 20000) $capacity = 75;
                elseif ($avgDonation > 5000) $capacity = 50;
                else $capacity = 25;

                // 3. Automated Segmentation
                // 3. FIX: Re-ordered logic to prioritize high capacity
                $segment = 'General Donor';

                if ($capacity >= 75) {
                    $segment = 'Potential Major Donor'; // High capacity = #1 priority
                } elseif ($likelihood > 60 && $capacity >= 50) {
                    $segment = 'Likely to Upgrade'; // Good engagement, decent capacity
                } elseif ($user->donations->count() > 0 && $likelihood < 40) {
                    $segment = 'At Risk of Lapsing'; // Has donated, but low engagement
                } elseif ($likelihood > 50 && $user->donations->count() == 0) {
                    $segment = 'Engaged Non-Donor'; // Active but hasn't given yet
                }

                // 4. Save the scores
                DonorScore::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'likelihood_score' => $likelihood,
                        'capacity_score' => $capacity,
                        'segment' => $segment,
                    ]
                );
            }
        });
        $this->info('Donor scores calculated successfully for all students and donors.');
    }
}