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
        // Get all universities
        $unizik = University::where('name', 'Nnamdi Azikiwe University')->first();
        $oau = University::where('name', 'Obafemi Awolowo University')->first();
        $unilag = University::where('name', 'University of Lagos')->first();
        $ui = University::where('name', 'University of Ibadan')->first();
        $unn = University::where('name', 'University of Nigeria, Nsukka')->first();

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
        // --- ADD 3 NEW ADMINS ---
        User::firstOrCreate(
            ['email' => 'admin.unilag@naijaedupact.com'],
            [
                'name' => 'UNILAG Admin',
                'password' => Hash::make('password'),
                'role' => 'universityadmin',
                'university_id' => $unilag->id,
            ]
        );
        User::firstOrCreate(
            ['email' => 'admin.ui@naijaedupact.com'],
            [
                'name' => 'UI Admin',
                'password' => Hash::make('password'),
                'role' => 'universityadmin',
                'university_id' => $ui->id,
            ]
        );
        User::firstOrCreate(
            ['email' => 'admin.unn@naijaedupact.com'],
            [
                'name' => 'UNN Admin',
                'password' => Hash::make('password'),
                'role' => 'universityadmin',
                'university_id' => $unn->id,
            ]
        );
        // --- END OF NEW ADMINS ---

        // 3. Alumnus (Donors)
        User::firstOrCreate(
            ['email' => 'alumnus.unizik@test.com'],
            ['name' => 'Test Alumnus (UNIZIK)', 'password' => Hash::make('password'), 'role' => 'donor', 'university_id' => $unizik->id]
        );
        User::firstOrCreate(
            ['email' => 'alumnus.oau@test.com'],
            ['name' => 'Test Alumnus (OAU)', 'password' => Hash::make('password'), 'role' => 'donor', 'university_id' => $oau->id]
        );
        User::firstOrCreate(
            ['email' => 'alumnus.unilag@test.com'],
            ['name' => 'Test Alumnus (UNILAG)', 'password' => Hash::make('password'), 'role' => 'donor', 'university_id' => $unilag->id]
        );
        User::firstOrCreate(
            ['email' => 'alumnus.ui@test.com'],
            ['name' => 'Test Alumnus (UI)', 'password' => Hash::make('password'), 'role' => 'donor', 'university_id' => $ui->id]
        );
        User::firstOrCreate(
            ['email' => 'alumnus.unn@test.com'],
            ['name' => 'Test Alumnus (UNN)', 'password' => Hash::make('password'), 'role' => 'donor', 'university_id' => $unn->id]
        );
        // ... (you can add more test alumni for the new unis if you want)

        // 4. Students
        User::firstOrCreate(
            ['email' => 'student.unizik@test.com'],
            ['name' => 'Test Student (UNIZIK)', 'password' => Hash::make('password'), 'role' => 'student', 'university_id' => $unizik->id, 'expected_graduation_year' => 2027]
        );
        User::firstOrCreate(
            ['email' => 'student.oau@test.com'],
            ['name' => 'Test Student (OAU)', 'password' => Hash::make('password'), 'role' => 'student', 'university_id' => $oau->id, 'expected_graduation_year' => 2026]
        );
        User::firstOrCreate(
            ['email' => 'student.unilag@test.com'],
            ['name' => 'Test Student (UNILAG)', 'password' => Hash::make('password'), 'role' => 'student', 'university_id' => $unilag->id, 'expected_graduation_year' => 2027]
        );
        User::firstOrCreate(
            ['email' => 'student.ui@test.com'],
            ['name' => 'Test Student (UI)', 'password' => Hash::make('password'), 'role' => 'student', 'university_id' => $ui->id, 'expected_graduation_year' => 2026]
        );
        User::firstOrCreate(
            ['email' => 'student.unn@test.com'],
            ['name' => 'Test Student (UNN)', 'password' => Hash::make('password'), 'role' => 'student', 'university_id' => $unn->id, 'expected_graduation_year' => 2025]
        );
        // ... (you can add more test students for the new unis if you want)
    }
}