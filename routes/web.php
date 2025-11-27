<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Item;
use App\Models\ProductView;
use App\Models\Task;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\DB;

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
    
    // 获取最新发布的8个商品，并带上卖家和分类信息
    $items = Item::with(['user', 'category'])->latest()->take(8)->get();
    
    // 如果商品为空，生成一些随机示例商品
    if ($items->isEmpty()) {
        $itemTitles = [
            '二手iPad Pro 11寸', '九成新MacBook Air', '闲置自行车', '考研英语资料全套',
            '吉他一把', '运动鞋42码', '四六级词汇书', '二手显示器', '考研政治资料',
            '闲置台灯', '二手键盘鼠标', '考研数学真题', '闲置书架', '二手耳机',
            '富士XT-30相机', 'Switch OLED游戏机', '人体工学椅', '升降学习桌', 'Kindle Scribe',
            '蓝牙黑胶音响', 'Wacom手绘板', '滑板一套', '羽毛球拍双拍', '脚踏电钢琴', '投影仪'
        ];
        $itemPrices = [149, 199, 249, 299, 349, 399, 459, 499, 559, 599, 699, 799, 899, 999, 1299, 1599, 1999, 2499];
        $dealPlaces = [
            '图书馆门口', '宿舍楼下', '食堂门口', '教学楼A座', '北区操场', '南区球馆',
            '东门咖啡厅', '创业街门口', '创新实验楼大厅', '生活区超市旁'
        ];
        $userNames = [
            '李晨曦','王语桐','陈嘉泽','刘若然','赵梓萱','孙以诺','周静怡','吴柏霖','郑雨琪','何子墨',
            '林沁蕾','唐欣怡','肖思远','欧阳楚河','江一诺','程子骞','罗嘉懿','韩文萱','谢宸宇','崔以恒',
            '章可柔','金昱辰','顾星辰','黎静妍','夏庭轩','袁沐阳','邵思齐','贺知航','孟嘉洛','方宇航'
        ];
        
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
            '求吉他教学','代取快递','考研英语辅导','四六级口语陪练','复试面试指导','代买早餐',
            '帮忙搬行李','考研数学答疑','代课签到','帮忙打印资料','寻找考研研友','求购二手教材',
            '视频剪辑协助','毕业季旅拍','小红书笔记排版','简历优化辅导','海报设计任务',
            '演讲稿润色','舞蹈表演协作','校园直播助理','短剧拍摄演员','求Babysitter','租借单反相机'
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
            '需要购买一些二手教材，价格合理即可',
            '想做校园vlog，寻剪辑同学合作',
            '毕业旅行需摄影同伴，会提供补贴',
            '希望有人帮忙排版小红书笔记，美化图片',
            '简历写作欠缺经验，寻求职大佬指点',
            '想参加设计比赛，需海报设计搭档',
            '演讲稿中文润色，提升语气和逻辑',
            '舞蹈队表演人员临时缺席，求替补',
            '校内品牌活动需直播助理，负责布景和互动',
            '短剧拍摄缺演员，台词简单，一天完成',
            '周末帮忙照看两小时小侄子，提供零食',
            '摄影社集训需借用单反两天，押金可付'
        ];
        $taskRewards = ['10元','15元','20元','25元','30元','35元','40元','50元','60元','80元','100元','可议'];
        $taskUserNames = [
            '李晨曦','王语桐','陈嘉泽','刘若然','赵梓萱','孙以诺','周静怡','吴柏霖','郑雨琪','何子墨',
            '林沁蕾','唐欣怡','肖思远','欧阳楚河','江一诺','程子骞','罗嘉懿','韩文萱','谢宸宇','崔以恒',
            '章可柔','金昱辰','顾星辰','黎静妍','夏庭轩','袁沐阳','邵思齐','贺知航','孟嘉洛','方宇航',
            '叶梓萱','蒲清雅','任景宸','蔺晓白','尹思哲','邢亦凡','费清泓','尤思颖','钟逸轩','池望舒'
        ];
        $taskStatuses = ['open', 'completed'];
        
        $tasks = collect(range(1, 5))->map(function ($index) use ($taskTitles, $taskContents, $taskRewards, $taskUserNames, $taskStatuses) {
            $title = $taskTitles[array_rand($taskTitles)];
            $content = $taskContents[array_rand($taskContents)];
            $reward = $taskRewards[array_rand($taskRewards)];
            $userName = $taskUserNames[array_rand($taskUserNames)];
            $status = $taskStatuses[array_rand($taskStatuses)];
            
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

    // Initialize as empty collection (never null)
    $recommendedProducts = collect();
    $minRecommendations = 4;

    if (auth()->check()) {
        $userId = auth()->id();

        // Get user's top 2 most viewed categories
        $topCategoryIds = ProductView::query()
            ->where('product_views.user_id', $userId)
            ->join('items', 'product_views.product_id', '=', 'items.id')
            ->where('items.status', 'on_sale')
            ->select('items.category_id', DB::raw('COUNT(*) as views'), DB::raw('MAX(product_views.updated_at) as last_viewed'))
            ->groupBy('items.category_id')
            ->orderByDesc('views')
            ->orderByDesc('last_viewed')
            ->limit(2)
            ->pluck('items.category_id');

        // Get recommendations from user's favorite categories
        if ($topCategoryIds->isNotEmpty()) {
            $recommendedProducts = Item::with(['user', 'category'])
                ->where('status', 'on_sale')
                ->whereIn('category_id', $topCategoryIds)
                ->where('user_id', '!=', $userId)
                ->whereDoesntHave('orders', function ($query) use ($userId) {
                    $query->where('buyer_id', $userId);
                })
                ->orderByDesc('updated_at')
                ->take(8)
                ->get();
        }
    }

    // Fill with random items if not enough recommendations
    if ($recommendedProducts->count() < $minRecommendations) {
        $needed = max(8 - $recommendedProducts->count(), $minRecommendations - $recommendedProducts->count());
        $excludeIds = $recommendedProducts->pluck('id')->toArray();
        
        $fallback = Item::with(['user', 'category'])
            ->whereIn('status', ['on_sale', 'active'])
            ->when(!empty($excludeIds), function ($query) use ($excludeIds) {
                $query->whereNotIn('id', $excludeIds);
            })
            ->inRandomOrder()
            ->take(max($needed, $minRecommendations))
            ->get();

        $recommendedProducts = $recommendedProducts->concat($fallback)->take(8);
    }

    // Final fallback: use latest items if still empty
    if ($recommendedProducts->isEmpty()) {
        $recommendedProducts = $items instanceof \Illuminate\Support\Collection 
            ? $items 
            : collect($items ?? []);
    }

    return view('welcome', compact('categories', 'items', 'tasks', 'recommendedProducts'));
});

Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')->middleware('auth');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Item management routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
});

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
Route::patch('/tasks/{task}/complete', [TaskController::class, 'markAsComplete'])->name('tasks.complete')->middleware('auth');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy')->middleware('auth');

// User routes
Route::middleware('auth')->group(function () {
    Route::get('/user/profile', function () {
        return view('user.profile');
    })->name('user.profile');
    
    Route::put('/user/profile', [AuthController::class, 'updateProfile'])->name('user.profile.update');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::post('/settings/notifications', [SettingsController::class, 'toggleNotification'])->name('settings.notifications.toggle');
    
    // Wishlist routes
    Route::get('/user/wishlist', [\App\Http\Controllers\UserController::class, 'wishlist'])->name('user.wishlist');
    Route::post('/wishlist/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/task/{id}', [WishlistController::class, 'toggleTask'])->name('wishlist.task.toggle');
    
    // Collection route
    Route::get('/my-collection', [\App\Http\Controllers\UserController::class, 'myCollection'])->name('user.collection');

    // Wallet routes
    Route::get('/user/wallet', [WalletController::class, 'index'])->name('user.wallet');
    Route::post('/user/wallet/recharge', [WalletController::class, 'recharge'])->name('user.wallet.recharge');
    
    // Chat routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ConversationController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/message', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');
    Route::post('/chat/start', [ChatController::class, 'startConversation'])->name('chat.start');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/create', [OrderController::class, 'createAndPay'])->name('orders.create');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    
    // Conversation routes
    Route::post('/conversations/start', [ConversationController::class, 'checkOrCreate'])->name('conversations.start');
    
    // Conversation messages API routes (for SPA)
    Route::get('/conversations/{id}/messages', [ConversationController::class, 'getMessages'])->name('conversations.messages.index');
    Route::post('/conversations/{id}/messages', [ConversationController::class, 'store'])->name('conversations.messages.store');
    Route::delete('/conversations/{id}', [ConversationController::class, 'destroy'])->name('conversations.destroy');
    
    // Chat data API route (for SPA)
    Route::get('/api/conversations/{id}', [ConversationController::class, 'getChatData'])->name('api.conversations.show');
});

Route::get('/user/published', [UserController::class, 'published'])->name('user.published')->middleware('auth');

Route::get('/user/orders', [OrderController::class, 'index'])->name('user.orders')->middleware('auth');
Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus')->middleware('auth');
Route::post('/orders/{id}/review', [OrderController::class, 'storeReview'])->name('orders.storeReview')->middleware('auth');

Route::get('/user/favorites', function () {
    return view('user.favorites');
})->name('user.favorites');

    Route::get('/user/notifications', [NotificationController::class, 'index'])->name('notifications.index');

// Authentication routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
