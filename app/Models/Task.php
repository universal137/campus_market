<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'content', 'reward', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the users who have wishlisted this task.
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'task_user')->withTimestamps();
    }

    /**
     * Check if the task is liked by a specific user.
     */
    public function isLikedBy(User $user): bool
    {
        return $this->wishlistedBy()->where('user_id', $user->id)->exists();
    }
}
