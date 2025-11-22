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

        // 2. 创建更多测试用户，保证商品/任务都能分布到不同账号
        $users = User::factory(25)->create();

        // 3. 让这些用户发布 160 个二手商品，覆盖更多关键词供搜索测试
        Item::factory(160)
            ->recycle($users)
            ->create();

        // 4. 让这些用户发布 80 个互助任务
        Task::factory(80)
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
