<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public const TYPE_DEPOSIT = 'deposit';
    public const TYPE_PAYMENT = 'payment';
    public const TYPE_INCOME = 'income';
    public const TYPE_REFUND = 'refund';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'reference_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Transaction belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

