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
        Schema::create('university_timeline_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->comment('Admin who posted')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->text('content');
            $table->string('media_type')->nullable(); // 'image' or 'video_embed'
            $table->text('media_url')->nullable(); // S3 path or YouTube/Vimeo embed URL
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_timeline_updates');
    }
};
