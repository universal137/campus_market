<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the conversation that owns the message.
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the user (sender) of the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}
