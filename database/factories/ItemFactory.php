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

        $titlePool = [
            '九成新 iPad',
            '闲置 Kindle 电子书',
            '考研英语真题套装',
            '人体工学学习椅',
            '可升降学习台灯',
            '静音折叠跑步机',
            '智能手写板',
            '宿舍投影仪',
            '蓝牙机械键盘',
            '专业画板工具箱',
        ];

        $descriptionPool = [
            '自用不到半年，主要用来看网课，保护得很好，送原装外壳+充电器。',
            '准备搬宿舍处理闲置，功能完好无拆修，可当面验货，有轻微划痕不影响使用。',
            '备战考试时购买的资料，现在用不上了，附赠我整理的笔记和错题本。',
            '买重了，现在低价出，学生价诚心可小刀，支持校内同城自提。',
            '夜跑基本靠它照明，续航长，灯光柔和，附赠备用电池。',
            '课题结束腾地方，设备保持干净，无异味，配件齐全可直接使用。',
            '支持蓝牙和 2.4G 双模连接，键帽已更换，敲击声音顺滑。',
            '画画专业用的工具箱，里面工具齐全，送几张我练习用的纸张。',
        ];

        return [
            'user_id' => User::factory(),
            // 优先绑定数据库中的分类，无数据时自动生成一个新的分类
            'category_id' => $persistedCategoryId ?? Category::factory(),
            'title' => fake()->randomElement($titlePool),
            'description' => fake()->randomElement($descriptionPool),
            'price' => fake()->randomFloat(2, 5, 500),
            'status' => 'on_sale',
        ];
    }
}
