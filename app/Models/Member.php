<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'membership_number',
        'name',
        'email',
        'phone',
        'category',
        'level',
        'status',
    ];

    // A member belongs to a membership level.
    public function membershipLevel(): BelongsTo
    {
        return $this->belongsTo(
            MembershipLevel::class,
            'level',
            'name'
        );
    }

    // A member can have multiple transactions.
    public function membershipTransactions(): HasMany
    {
        return $this->hasMany(MembershipTransaction::class);
    }

    // Determine membership validity from approved transactions.
    public function getMembershipValidityAttribute(): string
    {
        $today = today()->toDateString();

        $transactions = $this->relationLoaded('membershipTransactions')
    ? $this->membershipTransactions
    : $this->membershipTransactions()
        ->where('status', 'Approved')
        ->whereNotNull('start_date')
        ->whereNotNull('end_date')
        ->get();

$transactions = $transactions->filter(function ($transaction) {
    return $transaction->status === 'Approved'
        && $transaction->start_date !== null
        && $transaction->end_date !== null;
});

        if ($transactions->isEmpty()) {
            return 'No Approved Membership';
        }

        // Check whether any approved period covers today.
        foreach ($transactions as $transaction) {
            $start = $transaction->start_date->toDateString();
            $end = $transaction->end_date->toDateString();

            if ($start <= $today && $end >= $today) {
                return 'Active';
            }
        }

        // Check whether a future approved period exists.
        foreach ($transactions as $transaction) {
            if ($transaction->start_date->toDateString() > $today) {
                return 'Upcoming';
            }
        }

        return 'Expired';
    }
}
