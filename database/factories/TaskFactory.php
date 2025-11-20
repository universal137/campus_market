<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;     // <--- 补上了这个

class TaskFactory extends Factory
{
    public function definition(): array
    {
        $titlePool = [
            '求助：图书馆代借资料',
            '求助：帮忙优化毕设 PPT',
            '求助：周末陪练羽毛球',
            '求助：校医院陪诊',
            '求助：搬宿舍顺路带件行李',
            '求助：寝室电费 AA 代缴',
            '求助：社团活动拍照记录',
            '求助：课程作业思路交流',
        ];

        $contentPool = [
            '课程作业需要一些参考资料，想请人顺路帮忙在图书馆借出并拍照回传，感谢！',
            '毕业答辩 PPT 想做得更美观专业，最好擅长配色和排版，晚上可在线沟通。',
            '最近想恢复运动，每周末上午约在体育馆，简单陪练即可，不要求水平。',
            '因为要做检查希望有人一起到校医院，帮忙取号和记录医生叮嘱。',
            '下周要把部分行李搬到新宿舍，想找人一起抬箱子，大概十分钟搞定。',
            '寝室电费又要充值了，能否帮忙顺便充一下，我及时转账，谢啦！',
            '社团论坛活动需要现场照片，最好有相机或手机像素高，拍完发原片即可。',
            '一门通识课的小组作业有点卡壳，希望找人一起讨论下思路和参考资料。',
        ];

        $rewardPool = ['一杯奶茶', '10元红包', '请吃饭', '面议', '咖啡券', '水果礼盒'];

        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement($titlePool),
            'content' => fake()->randomElement($contentPool),
            'reward' => fake()->randomElement($rewardPool),
            'status' => 'open',
        ];
    }
}
