<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipLevel extends Model
{
    protected $fillable = [
        'name',
        'fee',
        'validity_months',
        'benefits',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'validity_months' => 'integer',
    ];
}
