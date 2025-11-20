<?php

use Illuminate\Support\Facades\Route;
use App\Models\Item;
use App\Models\Task;
use App\Models\Category;

Route::get('/', function () {
    // 获取所有分类
    $categories = Category::all();
    // 获取最新发布的6个商品，并带上卖家信息
    $items = Item::with('user')->latest()->take(6)->get();
    // 获取最新发布的5个求助，带上发布人信息
    $tasks = Task::with('user')->latest()->take(5)->get();

    return view('welcome', compact('categories', 'items', 'tasks'));
});
