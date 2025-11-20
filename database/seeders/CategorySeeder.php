<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => '教材书籍', 'icon' => 'book'],
            ['name' => '数码电子', 'icon' => 'laptop'],
            ['name' => '生活用品', 'icon' => 'coffee'],
            ['name' => '美妆服饰', 'icon' => 'shirt'],
            ['name' => '其他闲置', 'icon' => 'box'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
