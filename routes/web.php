<?php

use Illuminate\Support\Facades\Route;
use App\Models\Item;
use App\Models\Task;
use App\Models\Category;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    // 获取所有分类
    $categories = Category::all();
    // 获取最新发布的6个商品，并带上卖家和分类信息
    $items = Item::with(['user', 'category'])->latest()->take(6)->get();
    // 获取最新发布的5个求助，带上发布人信息
    $tasks = Task::with('user')->latest()->take(5)->get();

    return view('welcome', compact('categories', 'items', 'tasks'));
});

Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

// User routes
Route::get('/user/profile', function () {
    return view('user.profile');
})->name('user.profile');

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

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Logout route
Route::post('/logout', function () {
    // In a real application, you would use Laravel's Auth facade
    // Auth::logout();
    // request()->session()->invalidate();
    // request()->session()->regenerateToken();
    
    return response()->json(['message' => 'Logged out successfully'], 200);
})->name('logout');
