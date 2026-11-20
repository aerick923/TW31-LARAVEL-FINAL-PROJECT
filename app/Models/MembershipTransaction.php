<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipTransaction extends Model
{
    protected $fillable = [
        'member_id',
        'transaction_type',
        'reference_number',
        'amount',
        'status',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    // Each transaction belongs to one member.
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // A transaction can have multiple payments.
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
