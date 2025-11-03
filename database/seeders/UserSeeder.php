<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\University;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the university IDs
        $unizik = University::where('name', 'Nnamdi Azikiwe University')->first();
        $oau = University::where('name', 'Obafemi Awolowo University')->first();

        if (!$unizik || !$oau) {
            $this->command->error('Universities not found. Please run the UniversitySeeder first.');
            return;
        }

        // 1. Superuser
        User::firstOrCreate(
            ['email' => 'superadmin@naijaedupact.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'university_id' => null,
            ]
        );

        // 2. University Admins
        User::firstOrCreate(
            ['email' => 'admin.unizik@naijaedupact.com'],
            [
                'name' => 'UNIZIK Admin',
                'password' => Hash::make('password'),
                'role' => 'universityadmin',
                'university_id' => $unizik->id,
            ]
        );
        User::firstOrCreate(
            ['email' => 'admin.oau@naijaedupact.com'],
            [
                'name' => 'OAU Admin',
                'password' => Hash::make('password'),
                'role' => 'universityadmin',
                'university_id' => $oau->id,
            ]
        );

        // 3. Alumnus (Donors)
        User::firstOrCreate(
            ['email' => 'alumnus.unizik@test.com'],
            [
                'name' => 'Test Alumnus (UNIZIK)',
                'password' => Hash::make('password'),
                'role' => 'donor',
                'university_id' => $unizik->id,
            ]
        );
        User::firstOrCreate(
            ['email' => 'alumnus.oau@test.com'],
            [
                'name' => 'Test Alumnus (OAU)',
                'password' => Hash::make('password'),
                'role' => 'donor',
                'university_id' => $oau->id,
            ]
        );

        // 4. Students
        User::firstOrCreate(
            ['email' => 'student.unizik@test.com'],
            [
                'name' => 'Test Student (UNIZIK)',
                'password' => Hash::make('password'),
                'role' => 'student',
                'university_id' => $unizik->id,
                'expected_graduation_year' => 2027,
            ]
        );
        User::firstOrCreate(
            ['email' => 'student.oau@test.com'],
            [
                'name' => 'Test Student (OAU)',
                'password' => Hash::make('password'),
                'role' => 'student',
                'university_id' => $oau->id,
                'expected_graduation_year' => 2026,
            ]
        );
    }
}
