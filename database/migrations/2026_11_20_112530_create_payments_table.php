
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Connect payment to a membership transaction
            $table->foreignId('membership_transaction_id')
                ->constrained('membership_transactions')
                ->restrictOnDelete();

            // Unique payment reference number
            $table->string('payment_reference')->unique();

            // Amount paid
            $table->decimal('amount', 10, 2);

            // Payment method
            $table->enum('payment_method', [
                'Cash',
                'GCash',
                'Bank Transfer'
            ]);

            // Date payment was recorded
            $table->date('payment_date');

            $table->timestamps();

            // Helps retrieve payment history
            $table->index([
                'membership_transaction_id',
                'payment_date'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
