<?php

use Illuminate\Support\Facades\Route;
use App\Models\Item;
use App\Models\Task;
use App\Models\Category;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    // 获取所有分类
    $categories = Category::all();
    
    // 如果分类为空，生成一些示例分类
    if ($categories->isEmpty()) {
        $categoryNames = ['电子产品', '图书资料', '生活用品', '运动器材', '学习用品', '服装配饰', '其他'];
        $categories = collect($categoryNames)->map(function ($name) {
            return (object) [
                'id' => rand(1, 1000),
                'name' => $name,
                'icon' => null,
            ];
        });
    }
    
    // 获取最新发布的6个商品，并带上卖家和分类信息
    $items = Item::with(['user', 'category'])->latest()->take(6)->get();
    
    // 如果商品为空，生成一些随机示例商品
    if ($items->isEmpty()) {
        $itemTitles = [
            '二手iPad Pro 11寸', '九成新MacBook Air', '闲置自行车', '考研英语资料全套',
            '吉他一把', '运动鞋42码', '四六级词汇书', '二手显示器', '考研政治资料',
            '闲置台灯', '二手键盘鼠标', '考研数学真题', '闲置书架', '二手耳机'
        ];
        $itemPrices = [299, 399, 599, 799, 1299, 1599, 199, 499, 899, 149, 299, 49, 199, 399];
        $dealPlaces = ['图书馆门口', '宿舍楼下', '食堂门口', '教学楼A座', '与卖家协商'];
        $userNames = ['小明', '小红', '小李', '小王', '小张', '小刘', '小陈', '小杨'];
        
        $items = collect(range(1, 6))->map(function ($index) use ($itemTitles, $itemPrices, $dealPlaces, $userNames, $categories) {
            $title = $itemTitles[array_rand($itemTitles)];
            $price = $itemPrices[array_rand($itemPrices)];
            $dealPlace = $dealPlaces[array_rand($dealPlaces)];
            $userName = $userNames[array_rand($userNames)];
            $category = $categories->random();
            
            return (object) [
                'id' => $index,
                'title' => $title,
                'price' => $price,
                'deal_place' => $dealPlace,
                'user' => (object) ['name' => $userName],
                'category' => $category,
            ];
        });
    }
    
    // 获取最新发布的5个求助，带上发布人信息
    $tasks = Task::with('user')->latest()->take(5)->get();
    
    // 如果任务为空，生成一些随机示例任务
    if ($tasks->isEmpty()) {
        $taskTitles = [
            '求吉他教学', '代取快递', '考研英语辅导', '四六级口语陪练',
            '复试面试指导', '代买早餐', '帮忙搬行李', '考研数学答疑',
            '代课签到', '帮忙打印资料', '寻找考研研友', '求购二手教材'
        ];
        $taskContents = [
            '想学吉他，希望有经验的同学可以教学，价格可议',
            '最近比较忙，希望有人可以帮忙代取快递，有偿',
            '考研英语需要辅导，希望有经验的学长学姐帮助',
            '准备四六级口语考试，需要陪练，可以互相练习',
            '复试在即，希望有经验的学长学姐指导面试技巧',
            '早上起不来，希望有人可以帮忙买早餐',
            '毕业季需要搬行李，希望有人帮忙',
            '考研数学遇到难题，希望有人可以答疑解惑',
            '临时有事，希望有人可以帮忙代课签到',
            '需要打印大量资料，希望有人可以帮忙',
            '准备考研，希望找到志同道合的研友一起学习',
            '需要购买一些二手教材，价格合理即可'
        ];
        $taskRewards = ['20元', '15元', '50元', '30元', '40元', '10元', '25元', '35元'];
        $userNames = ['小明', '小红', '小李', '小王', '小张', '小刘', '小陈', '小杨'];
        $statuses = ['open', 'completed'];
        
        $tasks = collect(range(1, 5))->map(function ($index) use ($taskTitles, $taskContents, $taskRewards, $userNames, $statuses) {
            $title = $taskTitles[array_rand($taskTitles)];
            $content = $taskContents[array_rand($taskContents)];
            $reward = $taskRewards[array_rand($taskRewards)];
            $userName = $userNames[array_rand($userNames)];
            $status = $statuses[array_rand($statuses)];
            
            return (object) [
                'id' => $index,
                'title' => $title,
                'content' => $content,
                'reward' => $reward,
                'status' => $status,
                'user' => (object) ['name' => $userName],
            ];
        });
    }

    return view('welcome', compact('categories', 'items', 'tasks'));
});

Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

// User routes
Route::middleware('auth')->group(function () {
    Route::get('/user/profile', function () {
        return view('user.profile');
    })->name('user.profile');
    
    Route::put('/user/profile', [AuthController::class, 'updateProfile'])->name('user.profile.update');
});

Route::get('/user/published', function () {
    return view('user.published');
})->name('user.published');

Route::get('/user/orders', function () {
    return view('user.orders');
})->name('user.orders');

Route::get('/user/favorites', function () {
    return view('user.favorites');
})->name('user.favorites');

Route::get('/user/notifications', function () {
    return view('user.notifications');
})->name('user.notifications');

Route::get('/settings', function () {
    return view('settings');
})->name('settings');

// Authentication routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
