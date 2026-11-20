<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'membership_transaction_id',
        'payment_reference',
        'amount',
        'payment_method',
        'payment_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    // Each payment belongs to one membership transaction.
    public function membershipTransaction(): BelongsTo
    {
        return $this->belongsTo(MembershipTransaction::class);
    }
}
