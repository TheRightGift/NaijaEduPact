<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\University;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all approved universities, ordered by ID
        $universities = University::where('status', 'approved')->orderBy('id')->get();
        
        // Get all universities *except* the last one
        $universitiesToSeed = $universities->slice(0, -1);
        
        $this->command->info('Seeding projects for ' . $universitiesToSeed->count() . ' universities...');

        foreach ($universitiesToSeed as $uni) {
            // Find the admin for this university
            $admin = User::where('university_id', $uni->id)
                         ->where('role', 'universityadmin')
                         ->first();

            if (!$admin) {
                $this->command->warn("No admin found for {$uni->name}, skipping projects.");
                continue;
            }

            $this->command->info("Seeding 3 projects for {$uni->name}...");

            // Seed 3 projects for this university
            for ($i = 1; $i <= 3; $i++) {
                $title = "Project {$i} for {$uni->name}";
                Project::firstOrCreate(
                    ['title' => $title],
                    [
                        'university_id' => $uni->id,
                        'user_id' => $admin->id,
                        'slug' => Str::slug($title) . '-' . uniqid(),
                        'description' => "This is a test description for {$title}. We are seeking funds to complete this amazing project.",
                        'goal_amount' => 1000000 * $i, // 1M, 2M, 3M
                        'current_amount' => 0,
                        'status' => 'active', // Set to 'active' so they appear on the site
                    ]
                );
            }
        }
        
        $this->command->info('Project seeding complete.');
    }
}