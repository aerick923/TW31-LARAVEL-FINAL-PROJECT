<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    protected $fillable = [
        'task_title',
        'description',
        'completed',
    ];

    // A task can be assigned to multiple users.
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->withPivot('completed', 'completed_at')
            ->withTimestamps();
    }
}