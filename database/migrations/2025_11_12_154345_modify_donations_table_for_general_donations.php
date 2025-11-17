<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add university_id as nullable AND make project_id nullable
        Schema::table('donations', function (Blueprint $table) {
            
            // THIS IS THE FIX:
            $table->foreignId('university_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('cascade') // Changed from 'set null'
                  ->after('user_id');

            $table->foreignId('project_id')->nullable()->change();
        });

        // --- Data Integrity Step ---
        
        // 2. Delete any orphaned donations
        DB::statement('
            DELETE d FROM donations d
            LEFT JOIN projects p ON d.project_id = p.id
            WHERE p.id IS NULL AND d.project_id IS NOT NULL
        ');
        
        // 3. Update all existing, valid donations
        DB::statement('
            UPDATE donations d
            JOIN projects p ON d.project_id = p.id
            SET d.university_id = p.university_id
            WHERE d.project_id IS NOT NULL
        ');

        // 4. Delete any donations that still have a NULL university_id
        DB::table('donations')->whereNull('university_id')->delete();

        // 5. Now, make university_id NOT nullable.
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('university_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropColumn('university_id');
            $table->foreignId('project_id')->nullable(false)->change();
        });
    }
};