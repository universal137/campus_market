<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category; // <--- 补上了这个
use App\Models\User;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        $persistedCategoryId = Category::query()->inRandomOrder()->value('id'); // 尝试复用已有分类，避免硬编码

        return [
            'user_id' => User::factory(),
            // 优先绑定数据库中的分类，无数据时自动生成一个新的分类
            'category_id' => $persistedCategoryId ?? Category::factory(),
            'title' => fake()->word() . ' ' . fake()->word(),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 5, 500),
            'status' => 'on_sale',
        ];
    }
}
