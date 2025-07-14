<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('qr_code')->nullable()->after('max_participants');
            $table->boolean('attendance_enabled')->default(true)->after('qr_code');
            $table->timestamp('attendance_deadline')->nullable()->after('attendance_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'attendance_enabled', 'attendance_deadline']);
        });
    }
};
