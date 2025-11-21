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
            'MacBook Pro 13 寸',
            '华为 MatePad 11',
            'Switch OLED 游戏机',
            '索尼 WH-1000XM5 耳机',
            '戴尔 27 寸 4K 显示器',
            '佳能入门级单反',
            '罗技 MX Master 鼠标',
            '四六级词汇书合集',
            '考研数学张宇套卷',
            '日语 N1 全套资料',
            '韩语自学教材',
            '室内单杠引体设备',
            '宿舍小冰箱',
            'Ulike 冷光脱毛仪',
            '九阳破壁机',
            '静音手摇咖啡磨豆机',
            'Gibson 民谣吉他',
            '小米扫地机器人',
            '校园储物柜租赁',
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
            '写论文时常用来跑 MATLAB，性能稳定，支持当面重装系统。',
            '包含全套四六级听力音频和配套纸质书，适合冲分备考党。',
            '佳能相机搭配 18-55mm 套机镜头，适合社团活动拍摄。',
            '附赠全新磨砂保护壳，屏幕贴膜刚换，电池循环不到 80 次。',
            '吉他弦已新换，琴盒琴调器送给你，适合校园路演表演。',
            '冷光脱毛仪有冰感模式，夏天宿舍使用很舒适，支持试用。',
            '买来准备开奶茶摊，结果没时间，设备八成新，送操作手册。',
            '包含 500G 学习资料合集：考研政治、408、设计手绘资源。',
            '显示器支持 Type-C 反向充电，MacBook 党必入。',
            '手摇磨豆机齿轮锋利，出粉均匀，附赠咖啡豆 200g。',
            '扫地机器人支持 APP 远程清扫，电池刚换没多久。',
        ];

        $dealPlacePool = [
            '东门喜茶门口',
            '南苑宿舍大厅',
            '图书馆一楼服务台旁',
            '体育馆正门台阶',
            '食堂二楼自助区',
            '创新楼大厅值班台',
            '知行广场喷泉旁',
            '创业园咖啡角',
            '研学楼 302 门口',
            '操场主席台左侧',
        ];

        return [
            'user_id' => User::factory(),
            // 优先绑定数据库中的分类，无数据时自动生成一个新的分类
            'category_id' => $persistedCategoryId ?? Category::factory(),
            'title' => fake()->randomElement($titlePool),
            'description' => fake()->randomElement($descriptionPool),
            'price' => fake()->randomFloat(2, 5, 500),
            'deal_place' => fake()->boolean(25) ? null : fake()->randomElement($dealPlacePool),
            'status' => 'on_sale',
        ];
    }
}
