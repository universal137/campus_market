<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id',
        'phone',
        'avatar',
        'school',
        'major',
        'enrollment_year',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the items that the user has published.
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Get the items that the user has wishlisted.
     */
    public function wishlist()
    {
        return $this->belongsToMany(Item::class, 'wishlists')->withTimestamps();
    }

    /**
     * Get the tasks that the user has published.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the tasks that the user has wishlisted.
     */
    public function wishlistTasks()
    {
        return $this->belongsToMany(Task::class, 'task_user')->withTimestamps();
    }

    /**
     * Get conversations where the user is the sender.
     */
    public function conversationsAsSender()
    {
        return $this->hasMany(Conversation::class, 'sender_id');
    }

    /**
     * Get conversations where the user is the receiver.
     */
    public function conversationsAsReceiver()
    {
        return $this->hasMany(Conversation::class, 'receiver_id');
    }

    /**
     * Get all conversations for the user (both as sender and receiver).
     * This returns a query builder that can be further chained.
     */
    public function conversations()
    {
        return Conversation::where('sender_id', $this->id)
            ->orWhere('receiver_id', $this->id);
    }

    /**
     * Get all messages sent by the user.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get orders where the user is the buyer.
     */
    public function ordersBought()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * Get orders where the user is the seller.
     */
    public function ordersSold()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /**
     * Get reviews received by the user.
     */
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    /**
     * Calculate the user's reputation score based on received reviews.
     */
    public function getReputationAttribute(): float
    {
        $average = $this->reviewsReceived()->avg('rating');

        return $average === null ? 5.0 : round((float) $average, 1);
    }

    /**
     * Get the avatar URL attribute.
     * Returns the full URL if avatar exists, null otherwise.
     * 
     * @return string|null
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        // If avatar is already a full URL (http:// or https://), return as is
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        // Otherwise, use Storage::url to generate the full URL
        return Storage::url($this->avatar);
    }

    /**
     * Determine whether the user has completed student verification.
     */
    public function getIsVerifiedAttribute(): bool
    {
        return !empty($this->student_id) && !empty($this->school);
    }
}
