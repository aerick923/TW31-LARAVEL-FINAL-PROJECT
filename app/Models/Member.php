<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    
public function membershipLevel()
{
    return $this->belongsTo(
        MembershipLevel::class,
        'level',
        'name'
    );
}


    // A member can have multiple membership transactions.
    public function membershipTransactions()
    {
        return $this->hasMany(MembershipTransaction::class);
    }


}
