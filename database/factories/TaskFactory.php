<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;     // <--- 补上了这个

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => '求助：' . fake()->sentence(3),
            'content' => fake()->paragraph(),
            'reward' => fake()->randomElement(['一杯奶茶', '10元红包', '请吃饭', '面议']),
            'status' => 'open',
        ];
    }
}
