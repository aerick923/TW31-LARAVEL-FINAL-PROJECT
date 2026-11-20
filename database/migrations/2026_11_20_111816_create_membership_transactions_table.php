<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_transactions', function (Blueprint $table) {
            $table->id();

            // Member associated with this transaction
            $table->foreignId('member_id')
                ->constrained('members')
                ->restrictOnDelete();

            // Application or renewal
            $table->enum('transaction_type', [
                'New Membership',
                'Renewal'
            ]);

            // Unique official transaction reference
            $table->string('reference_number')->unique();

            // Membership fee at the time of transaction
            $table->decimal('amount', 10, 2);

            // Transaction status
            $table->enum('status', [
                'Pending',
                'Paid',
                'Approved',
                'Expired',
                'Cancelled'
            ])->default('Pending');

            // Membership validity dates
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamps();

            // Useful for transaction history
            $table->index(['member_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_transactions');
    }
};
