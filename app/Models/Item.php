<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'description',
        'price', 'deal_place', 'image', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the users who have wishlisted this item.
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    /**
     * Check if the item is liked by a specific user.
     */
    public function isLikedBy(User $user): bool
    {
        return $this->wishlistedBy()->where('user_id', $user->id)->exists();
    }

    /**
     * Get all orders for this product.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    /**
     * Get the order for this product (usually one).
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'product_id');
    }

    /**
     * Get all conversations related to this product.
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'product_id');
    }
}
