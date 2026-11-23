<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            $table->foreignId('membership_level_id')
                ->nullable()
                ->constrained('membership_levels')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            $table->dropForeign(['membership_level_id']);
            $table->dropColumn('membership_level_id');
        });
    }
};