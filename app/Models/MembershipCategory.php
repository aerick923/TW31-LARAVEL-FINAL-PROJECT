<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipCategory extends Model
{
    protected $fillable = [
        'name',
        'level',
        'fee',
        'is_active'
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(MembershipApplication::class);
    }
}
