<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\University;
use Illuminate\Support\Str;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Existing Universities
        University::firstOrCreate(
            ['name' => 'Nnamdi Azikiwe University'],
            [
                'slug' => Str::slug('Nnamdi Azikiwe University'),
                'description' => 'Nnamdi Azikiwe University (UNIZIK) is a Federal university in Nigeria.',
                'status' => 'approved'
            ]
        );

        University::firstOrCreate(
            ['name' => 'Obafemi Awolowo University'],
            [
                'slug' => Str::slug('Obafemi Awolowo University'),
                'description' => 'Obafemi Awolowo University (OAU) is a federal government-owned university in Ile-Ife.',
                'status' => 'approved'
            ]
        );

        // --- ADD THESE 3 NEW UNIVERSITIES ---
        University::firstOrCreate(
            ['name' => 'University of Lagos'],
            [
                'slug' => Str::slug('University of Lagos'),
                'description' => 'University of Lagos (UNILAG), a leading institution dedicated to quality teaching, learning, research and community service.',
                'status' => 'approved'
            ]
        );

        University::firstOrCreate(
            ['name' => 'University of Ibadan'],
            [
                'slug' => Str::slug('University of Ibadan'),
                'description' => 'The University of Ibadan (UI) is the oldest degree-awarding institution in Nigeria.',
                'status' => 'approved'
            ]
        );
        
        University::firstOrCreate(
            ['name' => 'University of Nigeria, Nsukka'],
            [
                'slug' => Str::slug('University of Nigeria, Nsukka'),
                'description' => 'The University of Nigeria, Nsukka (UNN) is a federal university in Enugu State, Nigeria.',
                'status' => 'approved'
            ]
        );
        // --- END OF NEW UNIVERSITIES ---
    }
}