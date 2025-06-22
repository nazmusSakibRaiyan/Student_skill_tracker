<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('club_manager', function (Blueprint $table) {
            $table->boolean('banned')->default(false)->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('club_manager', function (Blueprint $table) {
            $table->dropColumn('banned');
        });
    }
};
