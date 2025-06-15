<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('name');
            $table->text('description')->nullable()->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn(['logo', 'description']);
        });
    }
};
