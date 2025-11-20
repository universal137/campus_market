<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon'];

    // 关联：一个分类下有很多商品
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
