<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 先创建分类
        $this->call(CategorySeeder::class);

        // 2. 创建 10 个测试用户
        $users = User::factory(10)->create();

        // 3. 让这些用户发布 50 个二手商品
        Item::factory(50)
            ->recycle($users)
            ->create();

        // 4. 让这些用户发布 20 个互助任务
        Task::factory(20)
            ->recycle($users)
            ->create();

        // 5. 管理员账号，如果已经存在就复用
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
            ]
        );
    }
}
