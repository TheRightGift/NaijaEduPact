<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('donor_name')->comment('Name of the matching donor');
            $table->decimal('match_amount', 15, 2);
            $table->string('challenge_type'); // e.g., 'donor_count', 'total_amount'
            $table->integer('challenge_threshold'); // e.g., 100 (donors), 500000 (amount)
            $table->string('status')->default('active'); // active, unlocked
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
