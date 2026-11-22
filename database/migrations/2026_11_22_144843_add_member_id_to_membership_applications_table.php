
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a member relationship to membership applications.
     */
    public function up(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            $table->foreignId('member_id')
                ->nullable()
                ->after('user_id')
                ->constrained('members')
                ->restrictOnDelete();
        });
    }

    /**
     * Remove the member relationship if rolled back.
     */
    public function down(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('member_id');
        });
    }
};
