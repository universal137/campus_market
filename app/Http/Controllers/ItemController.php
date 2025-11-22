<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Database\Factories\ItemFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $query = Item::with(['user', 'category'])->latest();

        $categoryId = $request->input('category');
        $hasCategoryFilter = $categoryId !== null && $categoryId !== '';
        if ($hasCategoryFilter) {
            $query->where('category_id', $categoryId);
        }

        $keyword = trim((string) $request->input('q'));
        if ($keyword !== '') {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $items = $query->paginate(12)->withQueryString();

        return view('items.index', [
            'items' => $items,
            'categories' => $categories,
            'filters' => [
                'category' => $hasCategoryFilter ? $categoryId : null,
                'q' => $keyword,
            ],
        ]);
    }

    public function show(Item $item): View
    {
        $item->load(['user', 'category']);

        return view('items.show', [
            'item' => $item,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'seller_name' => ['required', 'string', 'max:50'],
            'seller_email' => ['required', 'email', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'deal_place' => ['nullable', 'string', 'max:120'],
        ]);

        $user = User::firstOrCreate(
            ['email' => $validated['seller_email']],
            [
                'name' => $validated['seller_name'],
                'password' => bcrypt('campus-demo'),
            ]
        );

        Item::create([
            'user_id' => $user->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'deal_place' => $validated['deal_place'] ?? null,
            'image' => ItemFactory::getRandomImageUrl(),
            'status' => 'on_sale',
        ]);

        return back()->with('success', '已成功发布商品，感谢分享你的闲置！');
    }
}

