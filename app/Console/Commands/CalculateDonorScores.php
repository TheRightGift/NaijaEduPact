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
        User::with('donations', 'activities')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $likelihood = 0;
                
                // 1. Likelihood Score (0-100)
                if ($user->last_login_at && $user->last_login_at->gt(now()->subMonths(3))) $likelihood += 20;
                if ($user->activities->where('activity_type', 'view_project')->count() > 5) $likelihood += 30;
                if ($user->donations->count() > 0) $likelihood += 50; // Big bonus for any donation
                $likelihood = min($likelihood, 100); // Cap at 100

                // 2. Capacity Score (0-100) - Based on donation history for now
                $capacity = 0;
                $avgDonation = $user->donations->avg('amount');
                if ($avgDonation > 50000) $capacity = 100;
                elseif ($avgDonation > 20000) $capacity = 75;
                elseif ($avgDonation > 5000) $capacity = 50;
                else $capacity = 25;

                // 3. Automated Segmentation
                $segment = 'General Donor';
                if ($likelihood > 70 && $capacity > 70) $segment = 'Potential Major Donor';
                elseif ($likelihood > 60 && $capacity < 50) $segment = 'Likely to Upgrade';
                elseif ($user->donations->count() > 0 && $likelihood < 40) $segment = 'At Risk of Lapsing';

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
        $this->info('Donor scores calculated successfully.');
    }
}
