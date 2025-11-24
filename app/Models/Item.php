<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'deal_place',
        'image',
        'status',
        'latitude',
        'longitude',
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

    /**
     * Get a normalized image URL regardless of storage format.
     */
    public function getImageUrlAttribute(): string
    {
        $path = $this->image_path ?? $this->image;

        if (empty($path)) {
            return 'https://via.placeholder.com/400x300?text=No+Image';
        }

        $appHost = parse_url(config('app.url'), PHP_URL_HOST);

        if (Str::startsWith($path, ['http://', 'https://'])) {
            $pathHost = parse_url($path, PHP_URL_HOST);

            if (!$pathHost || ($appHost && $pathHost === $appHost)) {
                $path = parse_url($path, PHP_URL_PATH) ?: $path;
            } else {
                return $path;
            }
        }

        $normalizedPath = ltrim($path, '/');
        $publicPrefix = 'storage/';

        if (Str::startsWith($normalizedPath, $publicPrefix)) {
            $storageRelative = Str::after($normalizedPath, $publicPrefix);
        } else {
            $storageRelative = $normalizedPath;
        }

        if (Storage::disk('public')->exists($storageRelative)) {
            return asset($publicPrefix . $storageRelative);
        }

        return 'https://via.placeholder.com/400x300?text=No+Image';
    }
}
