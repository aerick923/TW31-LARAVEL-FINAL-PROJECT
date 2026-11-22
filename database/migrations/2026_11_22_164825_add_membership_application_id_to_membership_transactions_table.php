
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_transactions', function (Blueprint $table) {
            $table->foreignId('membership_application_id')
                ->nullable()
                ->after('member_id')
                ->constrained('membership_applications')
                ->restrictOnDelete();

            $table->unique('membership_application_id');
        });
    }

    public function down(): void
    {
        Schema::table('membership_transactions', function (Blueprint $table) {
            $table->dropUnique([
                'membership_application_id'
            ]);

            $table->dropConstrainedForeignId(
                'membership_application_id'
            );
        });
    }
};
