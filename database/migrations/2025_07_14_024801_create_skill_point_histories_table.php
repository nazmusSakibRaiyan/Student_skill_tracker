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
        Schema::create('skill_point_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_skill_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->integer('points_awarded');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->enum('action_type', ['award', 'deduct'])->default('award');
            $table->timestamp('awarded_at');
            $table->timestamps();
            
            $table->index(['student_skill_id', 'awarded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_point_histories');
    }
};
