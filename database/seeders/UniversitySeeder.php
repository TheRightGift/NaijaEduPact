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
        University::firstOrCreate(
            ['name' => 'Nnamdi Azikiwe University'],
            [
                'slug' => Str::slug('Nnamdi Azikiwe University'),
                'description' => 'Nnamdi Azikiwe University (UNIZIK) is a Federal university in Nigeria. It is one of the federal universities which are overseen and accredited by the National Universities Commission.',
                'status' => 'approved'
            ]
        );

        University::firstOrCreate(
            ['name' => 'Obafemi Awolowo University'],
            [
                'slug' => Str::slug('Obafemi Awolowo University'),
                'description' => 'Obafemi Awolowo University (OAU) is a federal government-owned university that is located in the ancient city of Ile-Ife, Osun State, Nigeria.',
                'status' => 'approved'
            ]
        );
    }
}
