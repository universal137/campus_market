<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // 管理员账号
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );

        // 测试用户1
        User::updateOrCreate(
            ['email' => 'test1@example.com'],
            [
                'name' => 'Test User 1',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );

        // 测试用户2
        User::updateOrCreate(
            ['email' => 'test2@example.com'],
            [
                'name' => 'Test User 2',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );

        // 测试用户3
        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('测试账号创建完成！');
        $this->command->info('管理员账号: admin@example.com / 123456');
        $this->command->info('测试账号1: test1@example.com / 123456');
        $this->command->info('测试账号2: test2@example.com / 123456');
        $this->command->info('学生账号: student@example.com / 123456');
    }
}

