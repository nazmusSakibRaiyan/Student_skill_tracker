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
        Schema::create('student_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_category_id')->constrained()->onDelete('cascade');
            $table->integer('total_points')->default(0);
            $table->integer('level')->default(1);
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->timestamps();
            
            $table->unique(['user_id', 'club_id', 'skill_category_id']);
            $table->index(['club_id', 'skill_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_skills');
    }
};
