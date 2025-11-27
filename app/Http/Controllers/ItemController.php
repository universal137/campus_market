<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ProductView;
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
            })
            ->orderByRaw("CASE 
                WHEN title LIKE ? THEN 1 
                WHEN description LIKE ? THEN 2 
                ELSE 3 
            END", ["%{$keyword}%", "%{$keyword}%"]);
        }

        $items = $query->paginate(12)->withQueryString();

        return view('items.index', [
            'items' => $items,
            'categories' => $categories,
            'filters' => [
                'category' => $hasCategoryFilter ? $categoryId : null,
                'q' => $keyword,
            ],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
        ]);
    }

    public function show(Item $item): RedirectResponse
    {
        return redirect()->route('products.show', $item);
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
            'images' => ['required_without:image', 'array', 'min:1', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'image' => ['required_without:images', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $user = User::firstOrCreate(
            ['email' => $validated['seller_email']],
            [
                'name' => $validated['seller_name'],
                'password' => bcrypt('campus-demo'),
            ]
        );

        $thumbnailPath = null;
        $galleryPaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                if (!$imageFile?->isValid()) {
                    continue;
                }

                $storedPath = $imageFile->store('products', 'public');
                $galleryPaths[] = $storedPath;

                if ($thumbnailPath === null) {
                    $thumbnailPath = $storedPath;
                }
            }
        }

        if ($thumbnailPath === null && $request->hasFile('image')) {
            $thumbnailPath = $request->file('image')->store('products', 'public');
            $galleryPaths[] = $thumbnailPath;
        }

        if ($thumbnailPath === null) {
            $thumbnailPath = ItemFactory::getRandomImageUrl();
        }

        if (empty($galleryPaths) && $thumbnailPath !== null) {
            $galleryPaths[] = $thumbnailPath;
        }

        Item::create([
            'user_id' => $user->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'deal_place' => $validated['deal_place'] ?? null,
            'image' => $thumbnailPath,
            'image_path' => $thumbnailPath,
            'gallery' => $galleryPaths,
            'status' => 'on_sale',
            'latitude' => $request->input('lat'),
            'longitude' => $request->input('lng'),
        ]);

        return back()->with('success', '已成功发布商品，感谢分享你的闲置！');
    }

    public function edit(Item $item): View
    {
        if (auth()->id() !== $item->user_id) {
            abort(403, '无权编辑此商品');
        }
        
        $categories = Category::orderBy('name')->get();
        $item->load(['category']);
        
        return view('items.edit', [
            'item' => $item,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        if (auth()->id() !== $item->user_id) {
            abort(403, '无权编辑此商品');
        }
        
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'deal_place' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:on_sale,sold'],
        ]);

        $item->update($validated);

        return redirect()->route('user.published')->with('success', '商品信息已更新！');
    }

    public function destroy(Item $item): RedirectResponse
    {
        if (auth()->id() !== $item->user_id) {
            abort(403, '无权删除此商品');
        }
        
        $item->delete();

        if (request()->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => '商品已删除']);
        }

        return redirect()->route('user.published')->with('success', '商品已删除');
    }
}

